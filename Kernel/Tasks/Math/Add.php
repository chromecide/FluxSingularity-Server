<?php

class KernelTasksMathAdd extends KernelTasksTask{
	public function __construct(){
		parent::__construct();
		
		$this->_ClassName = 'Kernel.Tasks.Math.Add';
		$this->_ClassTitle='Add';
		$this->_ClassDescription = 'Take 2 numbers as inputs, adds them together and outputs the result.';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.8.0';

		//Inputs
		$this->inputs['Input1'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Input1', 'Type'=>'Kernel.Data.Primitive.Number', 'Required'=>true, 'AllowList'=>false));
		$this->inputs['Input2'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Input2', 'Type'=>'Kernel.Data.Primitive.Number', 'Required'=>true, 'AllowList'=>false));
		
		//Outputs
		$this->outputs['Result'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Result', 'Type'=>'Kernel.Data.Primitive.Number'));
	}

	public function runTask(){
		if(!parent::runTask()){
			return false;
		}

		//Load Inputs
		$Input1 = $this->getTaskInput('Input1');
		$Input2 = $this->getTaskInput('Input2');

		$input1Val = $Input1->getValue();
		$input2Val = $Input2->getValue(); 
		$result = $input1Val + $input2Val;
		
		//code here
		$this->setTaskOutput('Result', DataClassLoader::createInstance('Kernel.Data.Primitive.Number',$result));
		
		return $this->completeTask();
	}
}
?>