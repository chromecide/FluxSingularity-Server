<?php

class KernelProcessesProcess extends KernelObject{
	const TRACE_LEVEL_DEBUG = 1;
	const TRACE_LEVEL_NOTICE = 2;
	const TRACE_LEVEL_ERROR = 3;
	const TRACE_LEVEL_TASK = 4;
	const TRACE_LEVEL_PROCESS = 5;
	
	public $trace_level = 1;
	
	protected $inputs = array();
	protected $outputs = array();
	
	protected $inputData = array();
	protected $outputData = array();
	
	protected $localData = array();
	protected $defaultData = array();
	
	protected $tasks = array();
	protected $taskMap = array();
	protected $taskMapReverse = array();
	
	protected $tokenTable = array();
	
	protected $enabledTasks = array();
	protected $completedTasks = array();
	
	protected $numLoops = 0;
	protected $maxLoops = 1000000;
	
	protected $processComplete = false;
	protected $trace = array();
	
	public function __construct($config){
		
		$this->_ClassName = 'Kernel.Processes.Process';
		$this->_ClassTitle='Base Process Object';
		$this->_ClassDescription = 'The basis for all Processes within the Flux Singularity platform';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com>';
		$this->_ClassVersion = '0.4.0';
		
		$this->inputs['Enabled'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Enabled', 'Type'=>'Kernel.Data.Primitive.Boolean', 'Required'=>false, 'AllowList'=>false));
		$this->inputs['Reset'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Reset', 'Type'=>'Kernel.Data.Primitive.Boolean', 'Required'=>true, 'DefaultValue'=>false, 'AllowList'=>false));
		
		$this->outputs['Errors'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Errors', 'Type'=>'Kernel.Data.Primitive.Error', 'AllowList'=>true));
		$this->outputs['Completed'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Completed', 'Type'=>'Kernel.Data.Primitive.Boolean', 'AllowList'=>false));
		
		if($config){
			$this->parseConfig($config);	
		}
	}


	/**
	 * Parse Functions
	 */
	public function parseConfig($config){
		if(!is_array($config)){
			return $this->addError('No Process Configuration Supplied', __LINE__);
		}else{
			if(array_key_exists('Definition', $config)){
				$this->parseDefinition($config['Definition']);
			}

			if(array_key_exists('Inputs', $config)){
				$this->parseInputs($config['Inputs']);
			}
		}
	} 
	
	public function parseDefinition($definition){
		$this->addTrace('Parsing Definition');
		if($definition){
			//Tasks
			if(array_key_exists('Tasks', $definition)){
				$this->parseTaskDefinitions($definition['Tasks']);
			}else{
				$this->addTrace('	Error: No Tasks Supplied');
				$this->addError('No Tasks Supplied', __LINE__);
			}
	
			//LocalData
			if(array_key_exists('LocalData', $definition)){
				$this->parseLocalData($definition['LocalData']);
			}
			
			//Inputs
			if(array_key_exists('Inputs', $definition)){
				$this->parseInputDefinitions($definition['Inputs']);
			}
				
			//Tasks
			if(array_key_exists('TaskMap', $definition)){
				$this->parseTaskMap($definition['TaskMap']);
			}else{
				$this->addError('No Task Map Supplied', __LINE__);
			}	
		}else{
			$this->addTrace('	Parsing Definition Complete');
		}

		$this->addTrace('Parsing Definition Complete');
		//print_r($this->trace);
	}
	
	public function parseTaskDefinitions($definition){
		$this->addTrace('	Parsing Tasks Definition');
		foreach($definition as $taskName=>$taskClassName){
			$this->addTrace('		Creating Task: '.$taskName.'('.$taskClassName.')');
			$this->tasks[$taskName] = DataClassLoader::createInstance($taskClassName);
		}
		$this->addTrace('	Parsing Tasks Definition Complete');
	}
	
	public function parseLocalData($definition){
		$this->addTrace('	Parsing Local Data Definition');
		foreach($definition as $dataName=>$dataValue){
			$this->addTrace('		'.$dataName);
			$this->setLocalDataValue($dataName, $dataValue);
		}
		$this->addTrace('	Parsing Local Data Definition Complete');
	}
	
	public function parseTaskMap($definition){
		$parsed = true;
		$this->addTrace('	Parsing Task Map Definition');
		foreach($definition as $sourceTaskName=>$attributes){
			foreach($attributes as $sourceAttributeName=>$targets){
				foreach($targets as $targetString){
					$targetParts = explode('.', $targetString);
					$targetTaskName = $targetParts[0];
					$targetAttributeName = $targetParts[1];
					
					if(!$this->validateTaskMapping($sourceTaskName, $sourceAttributeName, $targetTaskName, $targetAttributeName)){
						$this->addTrace('Invalid Task Mapping: '.$sourceTaskName.'.'.$sourceAttributeName.' => '.$targetTaskName.'.'.$targetAttributeName);
						$parsed = false;
						break 3;
					}else{
						if(!array_key_exists($targetTaskName, $this->taskMapReverse)){
							$this->taskMapReverse[$targetTaskName] = array();
						}
						
						if(!array_key_exists($targetAttributeName, $this->taskMapReverse[$targetTaskName])){
							$this->taskMapReverse[$targetTaskName][$targetAttributeName] = array();
						}
						$this->taskMapReverse[$targetTaskName][$targetAttributeName][] = $sourceTaskName.'.'.$sourceAttributeName;	
					}	
				}
			}
		}
		if($parsed){
			$this->taskMap = $definition;
			$this->addTrace('	Parsing Task Map Definition Complete');	
		}else{
			$this->addTrace('	Parsing Task Map Definition Failed');
		}
		return $parsed;
	}
	
	public function validateTaskMapping($sourceTaskName, $sourceAttributeName, $targetTaskName, $targetAttributeName){
		$valid = false;
		
		$sourceAttribute = false;
		$targetAttribute = false;
		
		
		if(!array_key_exists($sourceTaskName, $this->tasks)){
			if($sourceTaskName!='Inputs' && $sourceTaskName!='LocalData'){
				$this->addError('Invalid Source Task Name Supplied: '.$sourceTaskName, __LINE__);	
			}
		}else{
			
			$sourceTask = $this->tasks[$sourceTaskName];
			$sourceAttribute = $sourceTask->getOutputDefinition($sourceAttributeName);
			
			if(!$sourceAttribute){
				$this->addError('Invalid Source Attribute Supplied: '.$sourceAttributeName, __LINE__);
			}
		}
		
		if(!array_key_exists($targetTaskName, $this->tasks)){
			if($targetTaskName!='Outputs' && $targetTaskName!='LocalData'){
				$this->addError('Invalid Target Task Name Supplied', __LINE__);
			}
		}else{
			$targetTask = $this->tasks[$targetTaskName];
			$targetAttribute = $targetTask->getInputDefinition($targetAttributeName);
			if(!$targetAttribute){
				$this->addError('Invalid target Attribute Supplied: '.$targetAttributeName, __LINE__);
			}
		}
		
		$sourceTypeString = 'source';
		$targetTypeString = 'target';
		
		if(!$sourceAttribute){
			switch($sourceTaskName){
				case 'Inputs':
					$sourceAttribute = $this->getInputDefinition($sourceAttributeName);
					if(!$sourceAttribute){
						echo $sourceAttributeName.' not found';
					}
					$sourceAttributeType = $sourceAttribute->getValue('Type');
					$sourceTypeString = $sourceAttributeType->getValue();	
					break;
				case 'LocalData':
					$sourceAttribute = $this->getLocalDataValue($sourceAttributeName);
					if($sourceAttribute){
						$sourceTypeString = $sourceAttribute->getClassName();	
					}
					break;
			}
		}else{
			$sourceType = $sourceAttribute->getValue('Type');
			if(!$sourceType){
				$this->addError('Could Not retrieve Source Type: '.$sourceTaskName.'.'.$sourceAttributeName, __LINE__);
			}else{
				$sourceTypeString = $sourceType->getValue();	
			}
		}
		
		if(!$targetAttribute){
			switch($sourceTaskName){
				case 'Outputs':
					$targetAttribute = $this->getOutputDefinition($targetAttributeName);
					$targetAttributeType = $targetAttribute->getValue('Type');
					$targetTypeString = $targetAttributeType->getValue();	
					break;
				case 'LocalData':
					$sourceAttribute = $this->getLocalDataValue($sourceAttributeName);
					if($sourceAttribute){
						$sourceTypeString = $sourceAttribute->getClassName();	
					}
					break;
			}
		}else{
			$targetType = $targetAttribute->getValue('Type');
			$targetTypeString = $targetType->getValue();
		}
		
		if($sourceTypeString!=$targetTypeString){
			if($targetTypeString=='Kernel.Data.Primitive.String'){
				//if the target type is string and the source type is a primitive type, allow it through
				if(substr($sourceTypeString, 0, 21)=='Kernel.Data.Primitive'){
					$valid = true;
				}else{
					$this->addError('Source and Target Types are not compatible: '.$sourceTaskName.'.'.$sourceAttributeName.'('.$sourceTypeString.')'.' => '.$targetTaskName.'.'.$targetAttributeName.'('.$targetTypeString.')', __LINE__);
				}
			}else{
				if(strpos($sourceTypeString, $targetTypeString)!=-1){//parent class of the source class
					$valid = true;
				}else{
					$this->addError('Source and Target Types are not compatible: '.$sourceTaskName.'.'.$sourceAttributeName.'('.$sourceTypeString.')'.' => '.$targetTaskName.'.'.$targetAttributeName.'('.$targetTypeString.')', __LINE__);	
				}	
			}
		}else{
			$valid = true;
		}
		return $valid;
	}
	
	public function parseInputDefinitions($definition){
		
		$this->addTrace('	Parsing Input Definitions');
		foreach($definition as $inputName=>$inputDef){
			$valid = true;
			
			if(array_key_exists('Name', $inputDef)){
				$name = $inputDef['Name'];
			}else{
				$valid = false;
				$this->addError('Input Definition Name not Supplied');
			}
			
			if(array_key_exists('Type', $inputDef)){
				$type = $inputDef['Type'];
			}else{
				$valid = false;
				$this->addError('Input Definition Type not Supplied');
			}
			
			if(array_key_exists('Required', $definition)){
				$required = $inputDef['Required'];
			}else{
				$required = false;
			}
			
			if(array_key_exists('AllowList', $definition)){
				$allowList = $inputDef['AllowList'];
			}else{
				$required = false;
			}
			
			if(array_key_exists('DefaultValue', $definition)){
				$defaultValue = $inputDef['DefaultValue'];
			}else{
				$defaultValue = null;
			}
			if($valid){
				$this->inputs[$inputName] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', $inputDef);
			}
		}
		$this->addTrace('	Parsing Input Definitions Complete');
	}
	
	public function parseInputs($definition){
		$this->addTrace('	Parsing Inputs');
		foreach($definition as $attributeName=>$attributeValue){
			$this->addTrace('		'.$attributeName);
			$this->setInputValue($attributeName, $attributeValue);
		}
		$this->addTrace('	Parsing Inputs Complete');
	}
	/**
	 * End Parse Functions
	 */
	
	/**
	 * Property Getters and Setters
	 */
	public function getInputDefinition($name){
		return $this->inputs[$name];
	}
	
	public function getOutputDefinition($name){
		return $this->outputs[$name];
	}
	
	public function setInputValue($name, $value){
		
		$definition = $this->getInputDefinition($name);
		$type = $definition->getValue('Type')->getValue();
		$typeString = str_replace('.', '', $type);
		
		if($value instanceof $typeString){
			$this->inputData[$name] = $value;		
		}else{
			$this->inputData[$name] = DataClassLoader::createInstance($type, $value);
		}
		
	}
	
	public function getInputValue($name){
		if(array_key_exists($name, $this->inputData)){
			return $this->inputData[$name];	
		}else{
			return null;
		}
	}
	
	public function setOutputValue($name, $value){
		$this->outputData[$name] = $value;
	}
	
	public function getOutputValue($name){
		if(array_key_exists($name, $this->outputData)){
			return $this->outputData[$name];	
		}else{
			return null;
		}
	}
	
	public function setLocalDataValue($name, $value){
		$this->localData[$name] = $value;
	}
	
	public function getLocalDataValue($name){
		if(array_key_exists($name, $this->localData)){
			return $this->localData[$name];	
		}else{
			return null;
		}
	}
	
	public function setDefaultValue($name, $value){
		$this->defaultData[$name] = $value;
	}
	
	public function getDefaultValue($name){
		if(array_key_exists($name, $this->defaultData)){
			return $this->defaultData[$name];	
		}else{
			return null;
		}
	}
	
	/**
	 * End Property Getters and Setters
	 */
	
	/**
	 * Token Table Functions
	 */
	public function setTokenValue($taskName, $attributeName, $value){
		
		$allowList = false;
		
		if(!array_key_exists($taskName, $this->tokenTable)){
			$this->tokenTable[$taskName] = array();
		}
			
		if($taskName=='LocalData' || $taskName=='Outputs'){
			
			if($taskName=='LocalData'){
				$this->setLocalDataValue($attributeName, $value);
				//$this->tokenTable['LocalData'][$attributeName] = $value;
				//echo $this->getLocalDataValue($attributeName)->getValue().'<br/>';
			}else{
				$this->setOutputValue($attributeName, $value);
				if($attributeName=='Completed'){
					//echo 'should be stopping<br/>';
					if($value->getValue()===true){
						$this->processComplete = true;	
					}
				}
			}
		}else{
			$task = $this->tasks[$taskName];
			//if the task attribute allows lists add to the list
			$attrDef = $task->getInputDefinition($attributeName);
			
			$allowListObj = $attrDef->getValue('AllowList');
			$allowList = $allowListObj->getValue();
			
			if($allowList){
				if(!array_key_exists($attributeName, $this->tokenTable[$taskName])){
					$this->tokenTable[$taskName][$attributeName] = array();			
				}
				$this->tokenTable[$taskName][$attributeName][] = $value;
			}else{
				$this->tokenTable[$taskName][$attributeName] = $value;	
			}
		}
		
		
		
		
		if($attributeName=='Enabled' || $attributeName=='Reset'){
				
			$setValue = $value->getValue();
			
			if($value->getValue()==true){
				$this->addTrace('					Enabled Task:'. $taskName);	
			}
			
			if($attributeName=='Reset'){
				$task = $this->tasks[$taskName];
				
				$task->resetTask();
				$this->tokenTable[$taskName] = array();
				
				$revAttrs = $this->taskMapReverse[$taskName];
				
				foreach($revAttrs as $revAttrName=>$revInputs){
				
					$this->tokenTable[$taskName][] = array();
					if($revAttrName!='Reset'){
						foreach($revInputs as $revInputString){
							
							$revParts = explode('.', $revInputString);
							$revInTaskName = $revParts[0];
							$revInAttrName = $revParts[1];
							
							switch($revInTaskName){
								case 'Inputs':
									$revAttrValue = $this->getInputValue($revInAttrName);
									break;
								case 'LocalData':
									$revAttrValue = $this->getLocalDataValue($revInAttrName);
									break;
								default:
									$revInTask = $this->tasks[$revInTaskName];
									$revAttrValue = $revInTask->getOutputValue($revInAttrName);//$this->getTokenValue($revInTaskName, $revInAttrName);		
									break;
							}

							
							if($revAttrValue){
								$this->setTokenValue($taskName, $revAttrName, $revAttrValue);	
							}
						}	
					}
					//print_r($revInputs);
					
				}
				$this->setTokenValue($taskName, 'Enabled', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
				//$this->tokenTable[$taskName]['Enabled'] = DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true);
			}
			$this->enabledTasks[$taskName] = $value->getValue();
		}
		
		if($attributeName=='Reset'){
			$this->addTrace('				Resetting: '.$taskName);
			
			$task = $this->tasks[$taskName];
			$task->setOutputValue('Completed', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));
			
			$this->enabledTasks[$taskName] = true;
			
			unset($this->completedTasks[$taskName]);
			
		}
		
		//update the token data for any tasks that rely on this data item
		if(array_key_exists($taskName, $this->taskMap)){
			$taskMapItems = $this->taskMap[$taskName];
			if(array_key_exists($attributeName, $taskMapItems)){
				$taskMapItem = $taskMapItems[$attributeName];
				foreach($taskMapItem as $targetString){
					$targetParts = explode('.', $targetString);
					$this->addTrace('					Updating Token Data:'. $targetString);
					$this->setTokenValue($targetParts[0], $targetParts[1], $value);
				}
			}	
		}
		
	}
	
	public function getTokenValue($task, $attribute){
		if($task=='LocalData' || $task=='Inputs'){
			if($task=='LocalData'){
				return $this->getLocalDataValue($attribute);	
			}else{
				return $this->getInputValue($attribute);
			}
		}
		
		if(array_key_exists($task, $this->tokenTable)){
			if(array_key_exists($attribute, $this->tokenTable[$task])){
				return $this->tokenTable[$task][$attribute];
			}else{
				$this->addError('No Token Table Entry for: '.$task.'.'.$attribute, __LINE__);	
			return false;
			}
		}else{
			$this->addError('No Token Table Entry for: '.$task, __LINE__);
			return false;
		}
	}
	
	public function loadTaskTokens($taskName){
		$this->addTrace('				Loading Tokens for: '.$taskName);
		$allSet = false;
		if(array_key_exists($taskName, $this->tokenTable)){
			$taskTokens = $this->tokenTable[$taskName];
			
			if(is_array($taskTokens)){
				if(array_key_exists($taskName, $this->tasks)){
					$taskObject = $this->tasks[$taskName];
					$allSet = true;
					foreach($taskTokens as $attributeName=>$tokens){
						if($attributeName!='Reset'){
							$this->addTrace('				: '. $attributeName);
							
							if(is_array($tokens)){
								foreach($tokens as $token){
									$taskObject->setInputValue($attributeName, $token);
								}
							}else{
								$taskObject->setInputValue($attributeName, $tokens);
							}
							if(!$allSet){
								$this->addError('Error Setting Task Value: '.$attributeName, __LINE__);
								break;
							}
								
						}
					}
				}else{
					$this->addError('Task Not Found: '.$taskName, __LINE__);
				}
			}else{
				$this->addError('No Token Table Entries for: '.$taskName, __LINE__);
			}
		}else{
			$this->addError('No Token Table Entry for: '.$taskName, __LINE__);
		}

		if($allSet){
			$this->tasks[$taskName] = $taskObject;
		}
		
		return $allSet;
	}
	
	public function transferTaskTokens($taskName){
		$this->addTrace('		Transfering Task Tokens: '.$taskName);
		$task = $this->tasks[$taskName];
		
		$taskMapEntries = $this->taskMap[$taskName];
		
		foreach($taskMapEntries as $attributeName=>$targetStrings){
			$this->addTrace('			'.$attributeName);
			$targetValue = $task->getOutputValue($attributeName);
			//$this->setTokenValue($taskName, $attributeName, $targetValue);
			
			foreach($targetStrings as $targetString){
				$this->addTrace('				'.$targetString);
				$targetParts = explode('.', $targetString);
				$this->setTokenValue($targetParts[0], $targetParts[1], $targetValue);
			}
		}
		$this->addTrace('		Transfering Task Tokens Complete: '.$taskName);
	}
	
	public function resetToken($task, $attribute){
		if(array_key_exists($task, $this->tokenTable)){
			if(array_key_exists($attribute, $this->tokenTable[$task])){
				$this->tokenTable[$task][$attribute] = array();
			}else{
				$this->addError('No Token Table Entry for: '.$task.'.'.$attribute, __LINE__);
			return false;
			}
		}else{
			$this->addError('No Token Table Entry for: '.$task, __LINE__);
			return false;
		}
	}
	
	public function resetTokens($task){
		if(array_key_exists($task, $this->tokenTable)){
			$this->tokenTable[$task] = array();
			return true;	
		}else{
			$this->addError('No Token Table Entry for: '.$task, __LINE__);	
			return false;
		}
	}
	
	/**
	 * End Token Table Functions
	 */
	
	/**
	 * Processing Functions
	 */
	
	/**
	 * PLEASE NOTE:  by the time we get to running this function,
	 * it is assumed that the process is valid , including all mappings
	 * 
	 */
	public function run(){
		//$processComplete = false;
		
		$this->addTrace('Starting Process: '.$this->getClassName(), self::TRACE_LEVEL_PROCESS);
		
		$this->addTrace('	Seeding Token Table');
		
		$inputs = $this->taskMap['Inputs'];
		
		foreach($inputs as $inputName=>$mappings){
			
			$itemData = $this->getInputValue($inputName);
			
			foreach($mappings as $mapping){
				$parts = explode('.', $mapping);
				$this->addTrace('	Setting Token: '.$mapping);
				
				$this->setTokenValue($parts[0], $parts[1], $itemData);
			}
		}
		
		if(array_key_exists('LocalData', $this->taskMap)){
			$localData = $this->taskMap['LocalData'];
		
			foreach($localData as $inputName=>$mappings){
				
				$itemData = $this->getLocalDataValue($inputName);
				$this->setTokenValue('LocalData', $inputName, $itemData);
				foreach($mappings as $mapping){
					$parts = explode('.', $mapping);
					$this->addTrace('	Setting Token: LocalData.'.$inputName.' => '.$mapping);
					$this->setTokenValue($parts[0], $parts[1], $itemData);
				}
			}	
		}
		
		
		$this->addTrace('	Seeding Token Table Complete');
		
		//run the process list
		$this->addTrace('	Starting Process Execution');
		
		
		if(count($this->enabledTasks)==0){
			$this->addTrace('		No Enabled Tasks upon Start: Process Aborting');
			$this->processComplete = true;
		}
		
		while(!$this->processComplete && $this->numLoops<=$this->maxLoops){
			$this->addTrace('		Starting Loop #'.$this->numLoops);
			$taskName = $this->getNextTaskName();
			if($taskName){
				if(!$this->runTask($taskName)){
					$this->addTrace('		Could not run Task: Process Aborting');
					$processComplete = true;
				}else{
					//$this->completedTasks[$taskName] = true;
					
				}
			}else{
				$this->addTrace('		No Enabled Tasks Found');
				$this->processComplete = true;
			}
			$this->addTrace('		Completed Loop #'.$this->numLoops);
			$this->numLoops++;
			
		}
		
		$this->addTrace('Process Execution Complete');
	}

	public function enableTask($taskName){
			
	}
	
	public function getNextTaskName(){	
		foreach($this->enabledTasks as $taskName=>$enabled){
			if($enabled){
				//print_r($this->completedTasks);
				if(!array_key_exists($taskName, $this->completedTasks)){
					return $taskName;
				}else{
					if($this->completedTasks[$taskName]==false){
						
					}else{
						//echo $taskName.' ::completed<br/>';	
					}
				}
			}
		}
		return false;
	}
	
	public function runTask($taskName){
		$this->addTrace('			Running Task: '.$taskName, self::TRACE_LEVEL_TASK);
		
		$this->loadTaskTokens($taskName);
		
		$task = $this->tasks[$taskName];
		
		if(!$task){
			$this->addError('Could not load Task', __LINE__);
			return false;
		}else{
			//run the task if it's ready
			//echo 'running task: '.$taskName.'<br/>';
			
			if($task->run()){
				$this->addTrace('		Task Run Complete');
				$this->transferTaskTokens($taskName);
			}else{
				
				$this->addTrace('		'.$taskName.': "Run" returned False');
				
				$processTrace = $task->getTrace();
				$this->trace = array_merge($this->trace, $processTrace);
				
				$taskErrors = $task->getOutputValue('Errors');
				$errorCount = $taskErrors->Count();
				
				for($x=0;$x<$errorCount;$x++){
					$item = $taskErrors->getItem($x);
					$this->addTaskError($item);	
				}
				
				$this->addTrace('		Added '.$errorCount.' errors');
			}
			
			$this->completedTasks[$taskName] = true;
		}	
		
		return true;
	}
	
	
	/**
	 * End Processing Functions
	 */
	
	/**
	 * Error and Trace Handling Functions
	 */
	public function addError($message, $lineNumber=0){
		$errors = $this->getOutputValue('Errors');
		
		if(!($errors instanceof KernelDataPrimitiveList)){
			$errors = DataClassLoader::createInstance('Kernel.Data.Primitive.List');
		}
		
		$errorItem = DataClassLoader::createInstance('Kernel.Data.Primitive.Error');
		$errorItem->setValue('Class', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $this->getClassName()));
		$errorItem->setValue('Message', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $message));
		$errorItem->setValue('Line', DataClassLoader::createInstance('Kernel.Data.Primitive.Number', $lineNumber));
		
		$errors->addItem($errorItem);
		$this->addTrace($message.'(Line '.$lineNumber.')', self::TRACE_LEVEL_ERROR);
		$this->setOutputValue('Errors', $errors);
	}

	public function addTaskError($errorItem){
		$errors = $this->getOutputValue('Errors');
		
		if(!($errors instanceof KernelDataPrimitiveList)){
			$errors = DataClassLoader::createInstance('Kernel.Data.Primitive.List');
		}
		
		$errors->addItem($errorItem);
		$class = $errorItem->getValue('Class');
		$message = $errorItem->getValue('Message');
		$lineNumber = $errorItem->getValue('Line');
		
		$this->addTrace($message->getValue().'('.$class->getValue().' - Line '.$lineNumber->getValue().')', self::TRACE_LEVEL_TASK);
		$this->setOutputValue('Errors', $errors);
	}
	
	public function addTrace($message, $level=self::TRACE_LEVEL_DEBUG){
		echo $message."\n";
		if($this->trace_level>=$level){
			$this->trace[] = array(
				'time'=>time(), 
				'msg'=>$message,
				'lvl'=>$level
			);	
		}
	}
	
	public function getTrace(){
		//print_r($this->trace);
		return $this->trace;
	}
	/**
	 * End Error and Trace Handling Functions
	 */
}
?>