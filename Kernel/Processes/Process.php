<?php
class KernelProcessesProcess extends KernelObject{
	protected $inputs = array();
	protected $outputs = array();
	
	protected $inputData = array();
	protected $outputData = array();
	
	protected $localData = array();
	
	protected $defaults = array();
	
	protected $tasks = array();
	protected $taskMap = array();
	
	protected $completedTasks = array();
	
	protected $tokens = array();
	
	protected $maxLoops = 100;
	protected $loops = 0;
	
	public $trace = array();
	
	public function __construct($config){
		
		parent::__construct($config);
		
		$this->_ClassName = 'Kernel.Processes.Process';
		$this->_ClassTitle='Base Process Object';
		$this->_ClassDescription = 'The basis for all Processes within the Flux Singularity platform';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.4.0';
		
		$this->inputs['Enabled'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Enabled', 'Type'=>'Kernel.Data.Primitive.Boolean', 'Required'=>false, 'AllowList'=>false));
		$this->inputs['Reset'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Reset', 'Type'=>'Kernel.Data.Primitive.Boolean', 'Required'=>true, 'DefaultValue'=>false, 'AllowList'=>false));
		
		$this->outputs['Errors'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Errors', 'Type'=>'Kernel.Data.Primitive.Error', 'AllowList'=>true));
		$this->outputs['Completed'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Completed', 'Type'=>'Kernel.Data.Primitive.Boolean', 'AllowList'=>false));
		
		if($config){
			$this->parseConfig($config);	
		}
		
	}
	
	public function parseConfig($config){
		if($config){
			if(array_key_exists('Definition', $config)){
				$this->parseDefinition($config['Definition']);
			}
			
			if(array_key_exists('Inputs', $config)){
				$this->loadInputs($config['Inputs']);
			}	
		}
	}
	
	public function loadInputs($inputs){
		foreach($inputs as $inputName=>$inputValue){
			$this->setProcessInput($inputName, $inputValue);
		}	
	}
	
	public function parseDefinition($config){
		
		if(array_key_exists('MaxLoops', $config)){
			$this->maxLoops = $config['MaxLoops'];
		}
		
		
		if(array_key_exists('Inputs', $config)){
			$inputs = $config['Inputs'];
			$this->trace[] = 'Parsing Inputs';
			foreach($inputs as $inputName=>$inputCfg){
				$this->trace[] = 'Parsing Input for: '.$inputName;
				if(!$inputCfg instanceof KernelDataPrimitiveTaskInput){
					$inputCfg = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', $inputCfg);
				}
				
				$this->inputs[$inputName] = $inputCfg;
			}	
		}
		
		if(array_key_exists('LocalData', $config)){
			$localData = $config['LocalData'];
			if($localData){
				//process local data
				foreach($localData as $dataName=>$dataItem){
					if(is_object($dataItem) && in_array('KernelData', class_parents($dataItem))){
						$this->setLocalData($dataName, $dataItem);
					}
				}
			}	
		}
		
		if(array_key_exists('Tasks', $config)){
			$tasks = $config['Tasks'];
			$this->trace[] = 'Parsing Tasks';
			foreach($tasks as $taskName=>$task){
				$this->trace[] = 'Parsing Task :'.$taskName;
				//print_r($task);
				if($task instanceof KernelTasksTask || $task instanceof KernelProcessesProcess){
					$this->tasks[$taskName] = $task;
				}else{
					$this->trace[] = 'Creating Instance of: '.$task;
					$this->tasks[$taskName] = DataClassLoader::createInstance($task);
				}
			}
		}
		
		if(array_key_exists('TaskMap', $config)){
			$this->trace[] = 'Parsing TaskMap';
			$taskMap = $config['TaskMap'];
			foreach($taskMap as $sourceTaskName=>$sourceOutputs){
				$this->trace[] = 'Parsing TaskMap For:'.$sourceTaskName;
				
				foreach($sourceOutputs as $sourceOutputName=>$mapItem){
					$this->trace[] = '     '.$sourceOutputName;
					foreach($mapItem as $targetInput){
						$this->taskMap[$sourceTaskName][] = array($sourceOutputName=>$targetInput);	
					}
				}
			}
		}
		if(array_key_exists('Defaults', $config)){
			foreach($config['Defaults'] as $task=>$value){
				$taskParts = explode('.', $task);
				$taskObj = $this->tasks[$taskParts[0]];
				$taskObj->setTaskInput($taskParts[1], $value);
				$this->defaults[$taskParts[0]][$taskParts[1]]= $value;
				//$this->tasks[$taskParts[0]] = $taskObj;
			}
		}
		
		$this->trace[] = 'Definition Parsing Complete';
		
	}
	
	public function getInputDefinition($name){
		return $this->inputs[$name];
	}
	
	public function getOutputDefinition($name){
		return $this->outputs[$name];
	}
	
	public function getInputList(){
		return $this->inputs;
	}
	
	public function getOutputList(){
		return $this->outputs;
	}
	
	public function getDefaultValue($taskName, $attributeName){
		$defTask = false;
		if(array_key_exists($taskName, $this->defaults)){
			$defTask = $this->defaults[$taskName];	
		}
		
		if($defTask){
			$defAttr = $defTask[$attributeName];
			if($defAttr){
				return $defAttr;
			}else{
				return null;
			}
		}else{
			return null;
		}
	}
	
	public function setInputValue($name, $value){
		//retrieve the field definition
		$fieldDef = $this->getInputDefinition($name);
		
		$targetValue = null;
		
		//ensure the field definition exists
		if($fieldDef && ($fieldDef instanceof KernelDataPrimitiveTaskInput)){
			$typeObj = $fieldDef->getValue('Type');
			$requiredObj = $fieldDef->getValue('Required');
			$allowedObj = $fieldDef->getValue('AllowList');
			
			$type = $typeObj->getValue();
			$required = $requiredObj->getValue();
			$allowList = $allowedObj->getValue();
			
			//supplied as the appropriate type?
			if($value instanceof $type){
				if($required && $value->getValue()==null){
					$this->addError($this->getClassName(), 'Field Required: '.$name);
					return false;
				}else{
					if($allowList){
						$targetValue = DataClassLoader::createInstance($type);
						$targetValue->addItem($value);
					}else{
						$targetValue = $value;	
					}
				}
			}else{
				if($required && $value==null){
					$this->addError($this->getClassName(), 'Field Required: '.$name);
					return false;
				}else{
					if($allowList && ($value instanceof KernelDataPrimitiveList)){
						$targetValue = $value;
					}else{
						if($value instanceof KernelDataPrimitiveList){
							$this->addError($this->getClassName(), 'Field Does not allow List Values: '.$name);
							return false;
						}else{
							$targetValue = DataClassLoader::createInstance($type, $value);	
						}
					}
				}
			}
		}else{
			$this->addError($this->getClassName(), 'Invalid Input Definition Format: '.$name);
			return false;
		}
		
		$this->inputData[$name] = $targetValue;
	}
	
	public function getInputValue($name){
		if($name && array_key_exists($name, $this->inputData)){
			return $this->inputData[$name];	
		}else{
			return null;
		}
	}
	
	public function setOutputValue($name, $value){
		//retrieve the field definition
		$fieldDef = $this->getOutputDefinition($name);
		
		$targetValue = null;
		
		//ensure the field definition exists
		if($fieldDef && ($fieldDef instanceof KernelDataPrimitiveTaskInput)){
			$typeObj = $fieldDef->getValue('Type');
			$requiredObj = $fieldDef->getValue('Required');
			$allowedObj = $fieldDef->getValue('AllowList');
			
			$type = $typeObj->getValue();
			$required = $requiredObj->getValue();
			$allowList = $allowedObj->getValue();
			
			//supplied as the appropriate type?
			if($value instanceof $type){
				if($required && $value->getValue()==null){
					$this->addError($this->getClassName(), 'Field Required: '.$name);
					return false;
				}else{
					if($allowList){
						$targetValue = DataClassLoader::createInstance($type);
						$targetValue->addItem($value);
					}else{
						$targetValue = $value;	
					}
				}
			}else{
				if($required && $value==null){
					$this->addError($this->getClassName(), 'Field Required: '.$name);
					return false;
				}else{
					if($allowList && ($value instanceof KernelDataPrimitiveList)){
						$targetValue = $value;
					}else{
						if($value instanceof KernelDataPrimitiveList){
							$this->addError($this->getClassName(), 'Field Does not allow List Values: '.$name);
							return false;
						}else{
							$targetValue = DataClassLoader::createInstance($type, $value);	
						}
					}
				}
			}
		}else{
			$this->addError($this->getClassName(), 'Invalid Input Definition Format: '.$name);
			return false;
		}
		
		$this->outputData[$name] = $targetValue;
	}
	
	public function getOutputValue($name){
		if($name && array_key_exists($name, $this->outputData)){
			return $this->outputData[$name];	
		}else{
			return null;
		}
	}	

	public function setTokenData($task, $param, $data){
		
		$taskObj = $this->tasks[$task];
		if(!$taskObj){
			echo 'task not loaded';
			echo $task.' - '.$param.'<br/>';
		}
		
		$field = $taskObj->getInputDefinition($param);
		
		if(!array_key_exists($task, $this->tokens)){
			$this->tokens[$task] = array();
		}
		
		if(!$field){
			echo $task.':'.$param.'<br/>';
		}else{
			if($field->getValue('AllowList')){
				if($field->getValue('AllowList')->getValue()==true){
					$newData = false;
					if(array_key_exists($param, $this->tokens[$task])){
						$newData =$this->tokens[$task][$param];
					}
					
					if(!($newData instanceof KernelDataPrimitiveList)){
						$newData = DataClassLoader::createInstance('Kernel.Data.Primitive.List');
					}
					$newData->addItem($data);
				}else{
					$newData = $data;
				}
			}else{
				$newData = $data;
			}
			
			$this->tokens[$task][$param] = $newData;	
		}
	}
	
	public function removeToken($task){
		unset($this->tokens[$task]);
	}
	
	public function run(){
		$this->trace[] = 'Begin Processing';
		
		$this->runPreprocessor();
		
		$this->processTokens();
		
		return $this->processTasks();
	}
	
	public function runProcess(){
		return $this->run();
	}
	
	public function runPreProcessor(){
		$this->tokens = array();
		$taskMap = $this->taskMap;
		$this->trace[] = '     Pre-Processing';
		
		try{
			foreach($taskMap as $taskName=>$taskMappings){
				$this->trace[] = '          -'.$taskName;
				foreach($taskMappings as $attributeMap){
					foreach($attributeMap as $sourceString=>$targetString){
						$this->trace[] = '               -'.$sourceString;
						
						switch($taskName){
							case 'Inputs':
								$targetValue = $this->getInputValue($sourceString);
								break;
							case 'LocalData':
								$targetValue = getLocalDataValue($sourceString);
								break;
							default:
								
								$taskObject = $this->tasks[$taskName];
								if($taskObject){
									$targetValue = $taskObject->getOutputValue($sourceString);	
								}else{
									$this->trace[] = 'Task Object Not Found: '.$taskName;
								}
								
								break; 
						}
						
						
						$targetParts = explode('.', $targetString);
						if(!is_array($targetParts) || count($targetParts)!=2){
							
							$this->addError($this->getClassName(), 'Invalid Target Path: '.$taskName.'.'.$sourceString.' -> '.$targetString);
							return false;
						}else{
							
							$targetTaskName = $targetParts[0];
							$targetInputName = $targetParts[1];
							
							if($targetInputName=='Reset'){
								
								if($targetValue && $targetValue->getValue()==true){
									//reset the input values for the object
									
									return $this->resetTask($taskName);
									
								}
							}
							
							if($targetValue==null){
								$targetValue= $this->getDefaultValue($targetTaskName, $targetInputName);
							}
							
							if($targetTaskName=='Outputs'){
								if($targetValue!==null){
									$this->setOutputValue($targetInputName, $targetValue);
								}
							}else{
								if($targetValue!==null){
									try{
										$this->setTokenData($targetTaskName, $targetInputName, $targetValue);	
									}catch (Exception $e){
										echo 'here';
										print_r($e);
									}
								}else{
									//echo 'not setting value<br/><br/>';
								}
								
							}
						}
					}
				}
			}
		}catch (Exception $e){
			print_r($e);
		}
		
		$this->trace[] = '     Pre-Processing Complete';
	}
	
	public function resetTask($taskName){
		$this->tokens[$taskName] = array();
		//return $this->runPreProcessor();
	}
	public function processTokens(){
		
		$tokens = $this->tokens;
		
		foreach($tokens as $task=>$tokenList){
			foreach($tokenList as $key=>$item){
				
				$taskObj = $this->tasks[$task];
				
				if(array_key_exists($task, $this->completedTasks)){
					$taskObj->setOutputValue('Completed', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
				}
				
				if($item!=null){
					$taskObj->setInputValue($key, $item);	
				}
				
				$this->tasks[$task] = $taskObj;
			}
		}
		
	}
	
	public function processTasks(){
		$this->loops++;
		$this->trace[] = 'Loop #:'.$this->loops;
		if($this->loops>$this->maxLoops){
			$this->trace[] = 'Loop Count Exceeded';
			$this->addError($this->getClassName(), 'Loop Count Exceeded');
			return false;
		}
		$tokens = $this->tokens;
		$tasksRun = 0;
		
		foreach($this->tasks as $taskName=>$task){
			$this->trace[] = 'Preparing Task: '.$taskName;
			$this->runPreProcessor($taskName);
			$this->processTokens();
			
			if($task->isReady()){
				try{
					$task->run();
					$tasksRun++;
					
					$completed = $task->getOutputValue('Completed');
					if($completed->getValue()){
						$this->completedTasks[$taskName] = true;
						$this->trace[] = 'Task Completed - '.$taskName;
					}else{
						$errored = $task->getOutputValue('ErrorOcurred');
						if($errored->getValue()==true){
							$errors = $task->getOutputValue('Errors');
							
							return false;
							
						}
					}
				}catch(Exception $e){
					print_r($e);
					die();
					$this->trace[] = $e;
				}
			}else{
				$this->trace[] = '   Not Ready';
			}
		}
		if($tasksRun>0){
			$this->trace[] = 'Processed Tasks: '.$tasksRun.'/'.count($this->tasks).'('.count($this->completedTasks).' Completed)';
			$this->processTasks();
		}else{
			$this->trace[] = 'Process Complete';
			
			$this->setProcessOutput('Completed', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
		}
		return true;
	}
	
	public function getTasks(){
		return $this->tasks;
	}
	
	public function getTokenData(){
		return $this->tokens;
	}
	
	public function getParameterMap(){
		return $this->parameterMap;
	}
	
	public function getInputs(){
		return $this->inputs;
	}
	
	public function getOutputs(){
		return $this->outputs;
	}
	
	public function getProcessInput($name){
		//print_r($this->inputData);
		if(array_key_exists($name, $this->inputData)){
			return $this->inputData[$name];	
		}else{
			return null;
		}
		
	}
	

	public function setProcessInput($input, $value){
		$field = $this->inputs[$input];
		
		if($field && $field->getValue('AllowList')){
			if($field->getValue('AllowList')->getValue()==true){
				$values = $this->getTaskInput($input);
				if($value instanceof KernelDataPrimitiveList){
					$values = $value;
				}
			}else{
				$type = $field->getValue('Type')->getValue();
				
				if($value instanceof $type){
					$values = $value;	
				}else{
					$values = DataClassLoader::createInstance($type, $value);
				}
			}
		}else{
			$type = $field->getValue('Type')->getValue();
			

			if($value instanceof $type){
				$values = $value;	
			}else{
				$values = DataClassLoader::createInstance($type, $value);
			}
		}
		$this->inputData[$input] = $values;
	}

	public function isReady(){
		//echo $this->getClassName().'<br/>';
		$enabled = $this->getProcessInput('Enabled');
		$completed = $this->getProcessOutput('Completed');
		$reset = $this->getProcessInput('Reset');
		
		if($enabled && $enabled->getValue()===true){
			if(!$completed || $completed->getValue()===false){
				return true;
			}else{
				if($reset && $reset->getValue()===true){
					return true;	
				}
			}
		}else{
			echo $this->getClassName().' not enabled<br/>';
		}
		
		return false;
	}

	public function getLocalData($name){
		return $this->localData[$name];
	}
	
	public function setLocalData($name, $value){
		$this->localData[$name] = $value;
	}

	public function getTaskDefault($taskName, $taskAttrName){
		if($this->defaults[$taskName]){
			$value = $this->defaults[$taskName][$taskAttrName];
			if($value){
				return $value;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	public function getProcessOutput($name){
		if(array_key_exists($name, $this->outputData)){
			return $this->outputData[$name];
		}else{
			return null;
		}
	}
	
	public function setProcessOutput($name, $value){
		$this->outputData[$name] = $value;
	}
	
	public function addError($className, $message, $errorNum=null){
		$errors = $this->getOutputValue('Errors');
		
		if(!($errors instanceof KernelDataPrimitiveList)){
			$errors = DataClassLoader::createInstance('Kernel.Data.Primitive.List');
		}
		
		$errorItem = DataClassLoader::createInstance('Kernel.Data.Primitive.Error');
		$errorItem->setValue('Class', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $className));
		$errorItem->setValue('Message', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $message));
		
		$errors->addItem($errorItem);
		
		$this->setOutputValue('Errors', $errors);
	}

}

?>