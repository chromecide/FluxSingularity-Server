<?php
class Kernel{
	
	const TRACE_LEVEL_DEBUG = 1;
	const TRACE_LEVEL_NOTICE = 2;
	const TRACE_LEVEL_ERROR = 3;
	const TRACE_LEVEL_TASK = 4;
	const TRACE_LEVEL_PROCESS = 5;
	
	public $trace_level = 1;
	
	protected $kernelStore = null;
	
	protected $errors = array();
	protected $trace = array();
	
	public static function getMeta(){
		$meta = $meta = new stdClass();
		$meta->Name = 'Kernel';
		$meta->Title = 'Core Kernel Object';
		$meta->Description = 'Provides the Core Entry Point for Flux Singularity';
		$meta->Author = 'Justin Pradier';
		$meta->Version = '0.4.0';
		
		return $meta;
	}
	
	public function __construct($config){
		$this->addTrace('Constructing Flux Singularity Kernel', 1);
		date_default_timezone_set('Australia/Melbourne');
		 
		DataClassLoader::loadClass('Kernel.Object');
		
		//Data
		DataClassLoader::loadClass('Kernel.Data');
		DataClassLoader::loadClass('Kernel.Data.Primitive');
			//Primitives
				DataClassLoader::loadClass('Kernel.Data.Primitive.NamedList');
				DataClassLoader::loadClass('Kernel.Data.Primitive.Boolean');
				DataClassLoader::loadClass('Kernel.Data.Primitive.Condition');
				DataClassLoader::loadClass('Kernel.Data.Primitive.DateTime');
				DataClassLoader::loadClass('Kernel.Data.Primitive.FieldDefinition');
				DataClassLoader::loadClass('Kernel.Data.Primitive.List');
				DataClassLoader::loadClass('Kernel.Data.Primitive.Number');
				DataClassLoader::loadClass('Kernel.Data.Primitive.String');
				DataClassLoader::loadClass('Kernel.Data.Primitive.TaskCondition');
				DataClassLoader::loadClass('Kernel.Data.Primitive.TaskInput');
				DataClassLoader::loadClass('Kernel.Data.Primitive.TaskOutput');
				
			//Data Store Drivers
			DataClassLoader::loadClass('Kernel.Data.Database');
			DataClassLoader::loadClass('Kernel.Data.DatabaseDriver');
		
		
			DataClassLoader::loadClass('Kernel.Data.Entity');
			
			//Tasks
			DataClassLoader::loadClass('Kernel.Tasks.Task');
			//Events
			DataClassLoader::loadClass('Kernel.Events.Event');
			//Process
			DataClassLoader::loadClass('Kernel.Processes.Process');
			
			//Security
			DataClassLoader::loadClass('Kernel.Security.Circle');
			DataClassLoader::loadClass('Kernel.Security.Permission');
			DataClassLoader::loadClass('Kernel.Security.User');
		
		if(!$this->parseConfig($config)){
			throw new Exception("Error Parsing Config", 1);
		}
		
		$this->addTrace('Constructing Flux Singularity Kernel Complete', 1);
	}
	
	public function parseConfig($config){
		$parsed = true;
		$this->addTrace('Parsing Configuration', 1);
		if($config){
			if(array_key_exists('KernelStore', $config)){
				$this->loadKernelStore($config['KernelStore']);
				KernelDataEntity::$kernelStore = $this->getKernelStore();
				KernelTasksTask::$kernelStore = $this->getKernelStore();
			}
	
			if(array_key_exists('User', $config)){
				$userAuthorised = $this->loadUser($config['User']);
				if(!$userAuthorised){
					$this->addError('Kernel', __LINE__, 'Invalid User Supplied');
					$parsed = false;
				}
			}else{
				//$this->addError('Kernel', __LINE__, 'No User Supplied');
				//$parsed = false;
			}	
		}
		$this->addTrace('Parsing Configuration Complete', 1);
		return $parsed;
	}
	
	public function getKernelStore(){
		return $this->kernelStore;
	}
	
	
	public function loadKernelStore($config){
		$store = DataClassLoader::createInstance('Kernel.Data.DataStore', $config);
		$this->kernelStore = $store;
	}
	
	public function loadUser($config){
		$authenticationTask = DataClassLoader::createinstance('Kernel.Tasks.Security.AuthenticateUser');
		
		$userName = DataClassLoader::createInstance('Kernel.Data.Primitive.String', $config['Username']);
		$password = DataClassLoader::createInstance('Kernel.Data.Primitive.String', $config['Password']);
		$store = $this->getKernelStore();
		
		$authenticationTask->setTaskInput('Username', $userName);
		$authenticationTask->setTaskInput('Password', $password);
		$authenticationTask->setTaskInput('Store', $store);
		
		$authenticationTask->enableTask();
		
		$authenticationTask->runTask();
		
		$authSucceededObj = $authenticationTask->getTaskOutput('AuthenticationSucceeded');
		
		$authSucceeded = $authSucceededObj->getValue();
		
		if($authSucceeded){
			$user = $authenticationTask->getTaskOutput('User');
				
			$this->user = $user;
			return true;
		}else{
			$this->user = null;
			return false;
		}
		
	}
	
	public function getUser(){
		return $this->user;
	}
	
	public function runTempProcess($processCfg){
		//print_r($processCfg);
		$process = DataClassLoader::createInstance('Kernel.Processes.Process', $processCfg);
		$process->run();
		return $process;	
	}
	
	public function runProcess($processName, $inputArray=array(), $outputHTMLResults=false){
		
		try{
			$process = DataClassLoader::createInstance($processName, $inputArray);
		}catch (Exception $e){
			echo 'Error Loading Class<br/><br/>';
			$this->errors[] = $e;
			return false;
		}
		
		/*if($process){
			if($inputArray && is_array($inputArray) && count($inputArray)>0){
				foreach($inputArray as $inputName=>$inputValue){
					$process->setProcessInput($inputName, $inputValue);
				}
			}
		}*/
		
		$results = $process->run();
		
		if($outputHTMLResults){
			
		}
		
		return $process;
	}
	
	public function runTask($taskName, $inputArray=array(), $outputHTMLResults=false){
		
		
		try{
			$task = DataClassLoader::createInstance($taskName);
		}catch (Exception $e){
			echo $e->getMessage();
			$this->errors[] = $e;
			return false;
		}
		
		
		if($task){
			if($inputArray && is_array($inputArray) && count($inputArray)>0){
				$inputList = $task->getInputList();
				foreach($inputArray as $inputName=>$inputValue){
					 $inputCfg=  $inputList[$inputName];
					//this needs to take into account an input array that is not Kernel.Data based
					//echo $inputName.' = '.$inputValue->getValue().'<br/>';
					if(is_object($inputValue)){
						if(in_array('KernelData', class_parents($inputValue))){ //Kernel.Data based
							$task->setTaskInput($inputName, $inputValue);	
						}else{//proabably a standard php variable
							$task->setTaskInput($inputName, DataClassLoader::createInstance($inputCfg->getValue('Type')->getValue(), $inputValue));
						}	
					}else{
						$task->setTaskInput($inputName, DataClassLoader::createInstance($inputCfg->getValue('Type')->getValue(), $inputValue));
					}	
				}
			}
			
			$task->run();
			
			
			if($outputHTMLResults){
				$this->printTaskOutputs($task);
			}
			
			return $task;
		}else{
			echo 'Could not create class';
		}
	}
	
	public function printTaskOutputs($task){
		$taskParams = $task->getInputList();
		echo '<h3>Running Task: '.$task->getClassName().'</h3>';
		echo '<table width="100%"><tr><th>Inputs</th><th>Outputs</th></tr>';
		echo '<tr><td valign="top">';
		echo '<table width="100%" border="1"><tr><th>Input Name</th><th>Input Value</th></tr>';
		foreach($taskParams as $paramName=>$paramCfg){
			$paramValue = $task->getTaskInput($paramName);
			echo '<tr>';
			echo '<td>';
			echo $paramName;
			echo '</td>';
			echo '<td>';
			
			if($paramValue && is_object($paramValue)){
				$className = $paramValue->getClassName();
				switch($className){
					case 'Kernel.Data.Primitive.Boolean';
						$rawValue = $paramValue->getValue();
						if($rawValue===true){
							echo 'True';
						}else{
							echo 'False';
						}
						break;
					case 'Kernel.Data.Primitive.List':
						echo $paramValue->toJSON();
						break;
					case 'Kernel.Data.DataStore':
						echo 'Supplied';
						break;
					default:
						echo $paramValue->getValue();
						break;
				}
			}
			echo '</td>';
			echo '</tr>';
		}
		echo '</table>';
		echo '</td><td valign="top">';
		echo '<table width="100%" border="1"><tr><th>Output Name</th><th>Output Value</th></tr>';
		$taskOutputs = $task->getOutputList();
		foreach($taskOutputs as $outputName=>$outputCfg){
			
			$outputValue = $task->getTaskOutput($outputName);
			echo '<tr>';
			echo '<td valign="top">';
			echo $outputName;
			echo '</td>';
			echo '<td style="overflow:auto;">';
			
			if($outputValue){
				$className = $outputValue->getClassName();
				
				switch($className){
					case 'Kernel.Data.Primitive.Boolean';
						$rawValue = $outputValue->getValue();
						if($rawValue===true){
							echo 'True';
						}else{
							echo 'False';
						}
						break;
					case 'Kernel.Data.Primitive.List':
						echo $outputValue->toJSON();
						break;
					default:
						if(in_array('KernelDataEntity', class_parents($outputValue))){
							echo $outputValue->getValue('KernelName')->getValue();
							//print_r($outputValue->getValue('KernelName'));
						}else{
							echo $outputValue->getValue();	
						}
						break;
				}
			}
			echo '</td>';
			echo '</tr>';
		}
		echo '</table>';
		echo '</td></tr>';
		echo '</table>';
	}
	
	public function fireEvent($eventName, $parameters){
		
	}
	
	public function addError($class, $line, $message){
		$errorItem = DataClassLoader::createInstance('Kernel.Data.Primitive.Error');
		$errorItem->setValue('Class', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $class));
		$errorItem->setValue('Message', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $message));
		$errorItem->setValue('LineNumber', DataClassLoader::createInstance('Kernel.Data.Primitive.Number', $line));
		
		$this->errors[] = $errorItem;	
	}
	
	public function getErrors(){
		return $this->errors;
	}
	
	public function addTrace($message, $level=self::TRACE_LEVEL_DEBUG){
		
		if($this->trace_level>=$level){
			$this->trace[] = array(
				'time'=>time(), 
				'msg'=>$message,
				'lvl'=>$level
			);	
		}
	}
	
	public function getTrace(){
		return $this->trace;
	}
	
}
?>