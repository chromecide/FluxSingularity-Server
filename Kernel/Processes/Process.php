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
	
	protected $parameterMap = array();
	
	protected $tokens = array();
	protected $maxLoops = 100;
	protected $loops = 0;
	
	protected $trace = array();
	
	public function __construct($config){
		parent::__construct($config);
		
		$this->_ClassName = 'Kernel.Processes.Process';
		$this->_ClassTitle='Base Process Object';
		$this->_ClassDescription = 'The basis for all Processes within the Flux Singularity platform';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.4.0';
		
		$this->inputs['Enabled'] = array('Enabled', 'Kernel.Data.Primitive.Boolean', true);
		
		$this->outputs['Errors'] = array('Errors', 'Kernel.Data.Primitive.String', true);
		$this->outputs['Completed'] = array('Completed', 'Kernel.Data.Primitive.Boolean');
		
		$this->parseConfig($config);
	}
	
	public function parseConfig($config){
		if($config['Definition']){
			$this->parseDefinition($config['Definition']);
		}
		
		if($config['Inputs']){
			$this->loadInputs($config['Inputs']);
		}
	}
	
	public function loadInputs($inputs){
		foreach($inputs as $inputName=>$inputValue){
			$this->setProcessInput($inputName, $inputValue);
		}	
	}
	
	public function parseDefinition($config){
		if($config['MaxLoops']){
			$this->maxLoops = $config['MaxLoops'];
		}
		
		$inputs = $config['Inputs'];
		foreach($inputs as $inputName=>$inputCfg){
			if(!$inputCfg instanceof KernelDataPrimitiveTaskInput){
				$inputCfg = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', $inputCfg);
			}

			$this->inputs[$inputName] = $inputCfg;
		}
		
		$localData = $config['LocalData'];
		if($localData){
			//process local data
			foreach($locaData as $dataName=>$dataItem){
				if(is_object($dataItem) && in_array('KernelData', class_parents($dataItem))){
					$this->setLocalData($dataName, $dataItem);
				}
			}
		}
		
		$tasks = $config['Tasks'];
		foreach($tasks as $taskName=>$task){
			if($task instanceof KernelTasksTask){
				$this->tasks[$taskName] = $task;
			}else{
				$this->tasks[$taskName] = DataClassLoader::createInstance($task);
			}
		}
		
		$taskMap = $config['TaskMap'];
		foreach($taskMap as $sourceTaskName=>$sourceOutputs){
			foreach($sourceOutputs as $sourceOutputName=>$mapItem){
				foreach($mapItem as $targetInput){
					$this->taskMap[$sourceTaskName][] = array($sourceOutputName=>$targetInput);	
				}
			}
		}
		
		if($config['Defaults']){
			foreach($config['Defaults'] as $task=>$value){
				$taskParts = explode('.', $task);
				$taskObj = $this->tasks[$taskParts[0]];
				$taskObj->setTaskInput($taskParts[1], $value);
				$this->defaults[$taskParts[0]][$taskParts[1]]= $value;
				//$this->tasks[$taskParts[0]] = $taskObj;
			}
		}
	}
	
	public function setInputEvent($name, $event){
		$this->inputEvents[$name] = $event;
	}
	
	public function setTokenData($task, $param, $data){
		$taskObj = $this->tasks[$task];
		if(!$taskObj){
			echo $task.':'.$param.'<br/>';
		}
		$field = $taskObj->getInputDefinition($param);
				
		if(!array_key_exists($task, $this->tokens)){
			$this->tokens[$task] = array();
		}
		
		if($field->getValue('AllowList')){
			if($field->getValue('AllowList')->getValue()==true){
				$newData = $this->tokens[$task][$param];
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
	
	public function removeToken($task){
		unset($this->tokens[$task]);
	}
	
	public function runProcess(){
		
		$this->runPreprocessor();
		$this->processTokens();
		return $this->processTasks();
	}
	
	public function runPreProcessor(){
		
		$this->tokens = array();
		$taskMap = $this->taskMap;
		
		foreach($taskMap as $task=>$mappings){
			foreach($mappings as $mapping){
				foreach($mapping as $sourceAttr=>$target){
					switch($task){
						case 'Inputs':
							$targetValue = $this->getProcessInput($sourceAttr);		
							break;
						case 'LocalData':
							$targetValue = $this->getLocalData($sourceAttr);		
							break;
						default:
							$taskObj = $this->tasks[$task];
							$targetValue = $taskObj->getTaskOutput($sourceAttr);
							break;
					}
					
					
					$targetParts = explode('.', $target);
					$targetTask = $targetParts[0];
					$targetInput = $targetParts[1];
					
					if(!$targetValue){
						//see if there is a default value for this item
						$targetValue = $this->getTaskDefault($targetParts[0], $targetParts[1]);
					}
					
					$this->setTokenData($targetTask, $targetInput, $targetValue);
				}	
			}	
		}
	}
	
	public function processTokens(){
		$tokens = $this->tokens;
		
		$this->trace[] = $tokens;
		foreach($tokens as $task=>$tokenList){
			foreach($tokenList as $key=>$item){
				$taskObj = $this->tasks[$task];
				
				if($this->completedTasks[$task]){
					$taskObj->setTaskOutput('Completed', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
				}
				
				$taskObj->setTaskInput($key, $item);
				$this->tasks[$task] = $taskObj;	
			}
		}
	}
	
	public function processTasks(){
		$this->trace[] = 'Begin Processing';
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
			$this->trace[] = 'Running Task: '.$taskName;
			$this->runPreProcessor();
			$this->processTokens();
			if($task->isReady()){
			$this->trace[] = 'Task Ready';
				try{
					$task->runTask();
					$tasksRun++;
					$completed = $task->getTaskOutput('Completed');
					
					if($completed->getValue()){
						$this->completedTasks[$taskName] = true;
						$this->trace[] = 'Task Completed';
					}
				}catch(Exception $e){
				}
			}
		}
		
		if($tasksRun>0){
			$this->processTasks();
		}else{
			$this->trace[] = 'Process Complete';
			
			$this->setProcessOutput('Complete', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
		}
		return true;
	}
	
	public function getInputEventData($name){
		return $this->inputEventData[$name];
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
		return $this->inputData[$name];
	}
	
	public function setProcessInput($name, $value){
		$this->inputData[$name] = $value;
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
		$errors = $this->getTaskOutput('Errors');
		
		$errorItem = DataClassLoader::createInstance('Kernel.Data.Primitive.Error');
		$errorItem->setValue('Class', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $className));
		$errorItem->setValue('Message', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $message));
		
		$errors->addItem($errorItem);
		
		$this->setTaskOutput('Errors', $errors);
	}

}

?>