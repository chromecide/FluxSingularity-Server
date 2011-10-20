<?php
class KernelTasksMathModulus extends KernelTasksTask{
	public function __construct(){
		parent::__construct(false);
		
		$this->_ClassName = 'Kernel.Tasks.Math.Modulus';
		$this->_ClassTitle='Modulus';
		$this->_ClassDescription = '';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.6.0';
		
		//Inputs

		$this->inputs['Input1'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Input1', 'Type'=>'Kernel.Data.Primitive.Number', 'Required'=>true, 'AllowList'=>false));
		$this->inputs['Input2'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Input2', 'Type'=>'Kernel.Data.Primitive.Number', 'Required'=>true, 'AllowList'=>false));
		
		//Outputs
		$this->outputs['Multiples'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Result', 'Type'=>'Kernel.Data.Primitive.Number'));
		$this->outputs['Remainder'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Result', 'Type'=>'Kernel.Data.Primitive.Number'));
	}

	public function runTask(){
		if(!parent::runTask()){
			return false;
		}
		//Defaults

		//Load Inputs
		$Input1 = $this->getTaskInput('Input1');
		$Input2 = $this->getTaskInput('Input2');

		$input1Val = $Input1->getValue();
		$input2Val = $Input2->getValue();
		 
		$result = $input1Val % $input2Val;
		$multiples = ($input1Val-$result)/$input2Val;
		
		$this->setTaskOutput('Multiples', DataClassLoader::createInstance('Kernel.Data.Primitive.Number', $multiples));
		$this->setTaskOutput('Remainder', DataClassLoader::createInstance('Kernel.Data.Primitive.Number', $result));
		
		return $this->completeTask();
	}
}
?>