<?php

class KernelTasksMathAdd extends KernelTasksTask{
	public function __construct(){
		parent::__construct(false);
		
		$this->_ClassName = 'Kernel.Tasks.Math.Add';
		$this->_ClassTitle='Add';
		$this->_ClassDescription = 'Take 2 numbers as inputs, adds them together and outputs the result.';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.6.0';

		//Inputs
		$this->inputs['Input1'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Input1', 'Type'=>'Kernel.Data.Primitive.Number', 'Required'=>true, 'AllowList'=>false));
		$this->inputs['Input2'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Input2', 'Type'=>'Kernel.Data.Primitive.Number', 'Required'=>true, 'AllowList'=>false));
		
		//Outputs
		$this->outputs['Result'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Result', 'Type'=>'Kernel.Data.Primitive.Number'));
		
		$this->outputs['Succeeded'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Succeeded', 'Type'=>'Kernel.Data.Primitive.Boolean'));
	}

	public function run(){
		$succeeded = true;
		if(!parent::run()){
			return false;
		}

		//Load Inputs
		$Input1 = $this->getTaskInput('Input1');
		$Input2 = $this->getTaskInput('Input2');
		
		if(!$Input1){
			$succeeded = false;
			$this->addError($this->getClassName(), 'Invalid Input Value Supplied: Input 1');
		}else{
			$input1Val = $Input1->getValue();
		}
		if(!$Input2){
			$succeeded = false;
			$this->addError($this->getClassName(), 'Invalid Input Value Supplied: Input 2');
		}else{
			$input2Val = $Input2->getValue();	
		}
		 
		if($succeeded){
			$result = $input1Val + $input2Val;
			$this->setTaskOutput('Result', DataClassLoader::createInstance('Kernel.Data.Primitive.Number', $result));	
		}
		
		$this->setTaskOutput('Succeeded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', $succeeded));
		
		return $this->completeTask();
	}
}
?>