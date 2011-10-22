<?php
/**
 * 
 * Convert a Process Instance to JSON Format
 * @author Justin Pradier <justin.pradier@fluxsingularity.com
 * @package Kernel.Tasks.Processes
 */

class KernelTasksProcessesConvertProcessToJSON extends KernelTasksTask{
	public function __construct(){
		parent::__construct(false);
		
		$this->_ClassName = 'Kernel.Tasks.Processes.ValidateProcess';
		$this->_ClassTitle='Validate Process';
		$this->_ClassDescription = 'Validates a Process';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.0.1';
		
		$this->inputs['Process'] = array('Process', 'Kernel.Processes.Process', true);
		$this->outputs['JSONString'] = array('JSONString', 'Kernel.Data.Primitive.String');
		
	}
	
	public function run(){
		$p = $this->getTaskInput('Process');
		
		if(!in_array('KernelProcessesProcess', class_parents($p))){
			echo 'invalid process supplied<br/>';
		}

		$obj = new stdClass();
		
		$obj->Title = $p->getTitle();
		$obj->Description = $p->getDescription();
		
		//Inputs
		$inputs = $p->getInputs();
		$inputObj = new stdClass();
		
		foreach($inputs as $name=>$input){
			$item = new stdClass();
			$type = $input[1];
			$required = $input[2];
			$acceptsList = $input[3];
			$defaultValue = $input[4];
			
			$item->Type = $type;
			$item->Required = $required===true?true:false;
			$item->AcceptsList = $acceptsList===true?true:false;
			
			if($defaultValue){
				$item->DefaultValue = $defaultValue->get();
			}
			
			$inputObj->$name = $item;
		}
		
		$obj->Inputs = $inputObj;
		
		//Outputs
		$outputs = $p->getOutputs();
		
		foreach($outputs as $name=>$output){
			$item = new stdClass();
			
			$type = $output[1];
			$returnsList = $output[2];
			
			$item->Type = $type;
			$item->ReturnsList = $returnsList===true?true:false;
		}
		
		//Token Data
		$tokens = $p->getTokenData();
		$tokenObj = new stdClass();
		
		foreach($tokens as $name=>$valueArray){
			$objArray = array();
			foreach($valueArray as $key=>$value){
				if(in_array('KernelDataPrimitive', class_parents($value))){//primitive base type
					$objArray[$key] = $value->get();
				}else{//entity base type
					$objArray[$key] = json_decode($value->toJSON());
				}
			}
			$tokenObj->$name = $objArray;
		}
		$obj->TokenData = $tokenObj;
		
		//Tasks
		$tasks = $p->getTasks();
		$tasksObj = new stdClass();
		
		foreach($tasks as $name=>$task){
			$tasksObj->$name = $task->getKernelClass();
		}
		
		$obj->Tasks = $tasksObj;
		
		//Task Map
		$map = $p->getParameterMap();
		$obj->TaskMap = $map;
		
		$this->setTaskOutput('JSONString', DataClassLoader::createInstance('Kernel.Data.Primitive.String', json_encode($obj)));
		$this->setTaskOutput('Completed',DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
	}
	
}
?>