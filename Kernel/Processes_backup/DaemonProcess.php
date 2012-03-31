<?php
 
class KernelProcessesDaemonProcess extends KernelObject{
	protected $title = 'Flux Singularity Process';
	protected $description = 'Flux Singularity Process';
	
	protected $author = 'Flux Singularity';
	
	protected $inputs = array();
	protected $outputs = array();
	
	protected $inputData = array();
	protected $outputData = array();
	
	protected $tasks = array();
	protected $parameterMap = array();
	
	protected $tokens = array();
	protected $loops = 0;
	
	public function __construct(){
		$this->_ClassName = 'Kernel.Processes.Processt';
		$this->_ClassTitle='Base Process Object';
		$this->_ClassDescription = 'The basis for all Processes within the Flux Singularity platform';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.1.0';
		
		$this->inputs['Enabled'] = array('Enabled', 'Kernel.Data.Primitive.Boolean', true);
		
		$this->outputs['Errors'] = array('Errors', 'Kernel.Data.Primitive.String', true);
		$this->outputs['Completed'] = array('Completed', 'Kernel.Data.Primitive.Boolean'); 
	}
	
	public function setInputEvent($name, $event){
		$this->inputEvents[$name] = $event;
	}
	
	public function setTokenData($task, $param, $data){
		if(!array_key_exists($task, $this->tokens)){
			$this->tokens[$task] = array();
		}
		$this->tokens[$task][$param] = $data;
	}
	
	public function removeToken($task){
		unset($this->tokens[$task]);
	}
	
	public function run(){
		System_Daemon::info('  Starting Process: %s', $this->KernelClass);
		foreach($this->parameterMap['Inputs'] as $input){
			foreach($input as $inputName=>$mapItem){
				$parts = explode('.', $mapItem);
				$object = $this->getProcessInput($inputName);
				$this->setTokenData($parts[0], $parts[1], $object);
			}
		}
		return $this->processTasks();
	}
	
	public function processTasks(){
		System_Daemon::info('    Processing Tasks: ');
		$this->loops++;
		/*if($this->loops>5){
			return;
		}*/
		$tokens = $this->tokens;
		$tasksRun = 0;
		//move all the tokens into their associated tasks
		if(count($tokens)>0){
			foreach($tokens as $task=>$params){
				//echo "Evaluating: $task\n";
				$taskObj = $this->tasks[$task];
				if(count($params)>0){
					foreach($params as $param=>$value){
						$taskObj->setTaskInput($param, $value);
					}
				}
				
				if($taskObj->isReady()){
					try{
						System_Daemon::info('      '.$task);
						//echo 'running '.$task."\n";
						$taskObj->runTask();
						$tasksRun++;
					}catch(Exception $e){
					//	echo "Error running task\n";
					//	print_r($e);
					}
					try{
						$completed = $taskObj->getTaskOutput('Completed');
						if($completed->get()===true){
							$this->setTokenData($task, 'Completed', $completed);
							$inputmap = $this->parameterMap[$task];
							
							foreach($inputmap as $mapping){
								foreach($mapping as $mapKey=>$mapValue){
									$paramValue = $taskObj->getTaskOutput($mapKey);
									
									$parts = explode('.', $mapValue);
									
									$this->setTokenData($parts[0], $parts[1], $paramValue);
								}	
							}
						}
					}catch(Exception $e){
						//echo "erorr doing postprocess\n";
						//print_r($e);
					}
				}
				//echo "EndEvaluating: $task\n\n";
			}
		}else{
			System_Daemon::info('No Tokens');	
		}
		
		if($tasksRun>0){
			//echo "--------------------------\n";
			$this->processTasks();
		}else{
			//echo "--------------------------\n";
			//echo "Process Complete\n";
			//echo "--------------------------\n";
		}
		return true;
	}
	
	public function getInputEventData($name){
		return $this->inputEventData[$name];
	}
	
	public function getTitle(){
		return $this->title;
	}
	
	public function getDescription(){
		return $this->description;
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
}

?>