<?php
class KernelTasksDataSetEntityAttribute extends KernelTasksTask{
	public function __construct(){
		parent::__construct(false);
		
		$this->_ClassName = 'Kernel.Tasks.Data.SetEntityAttribute';
		$this->_ClassTitle='Set Entity Attribute';
		$this->_ClassDescription = 'Sets an Entity\'s Attribute Value';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.5.0';

		//Inputs
		$this->inputs['Entity'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Entity', 'Type'=>'Kernel.Data.Entity', 'Required'=>true));
		$this->inputs['AttributeName'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Attribute', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true));
		$this->inputs['Value'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Value', 'Type'=>'Kernel.Data', 'Required'=>true));
		
		//Outputs
		$this->outputs['Entity'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Entity', 'Type'=>'Kernel.Data.Entity', 'Required'=>true));
		$this->outputs['AttributeSet'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Attribute Set', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true));
		$this->outputs['AttributeNotSet'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Attribute Not Set', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true));
	}

	public function runTask(){
		//Defaults

		//Load Inputs
		$Entity = $this->getTaskInput('Entity');
		$AttributeName = $this->getTaskInput('AttributeName');
		$Value = $this->getTaskInput('Value');
		
		//code here
		$Entity->set($AttributeName->getValue(), $Value);
		
		$this->setTaskOutput('Entity', $Entity);
		$this->setTaskOutput('AttributeSet', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
		$this->setTaskOutput('AttributeNotSet', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));

		parent::runTask();
	}
}
?>