<?php
class KernelTasksDataGetEntityAttribute extends KernelTasksTask{
	public function __construct(){
		parent::__construct();
		
		$this->_ClassName = 'Kernel.Tasks.Data.GetEntityAttribute';
		$this->_ClassTitle='Gets Entity Attribute';
		$this->_ClassDescription = 'Retrieves the value of an Entity Attribute';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.8.0';
		
		//Inputs
		$this->inputs['Entity'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Entity', 'Type'=>'Kernel.Data.Entity', 'Required'=>true));
		$this->inputs['AttributeName'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Attribute Name', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true));
		
		//Outputs
		$this->outputs['AttributeValue'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Attribute Value', 'Type'=>'Kernel.Data', 'Required'=>true));
		$this->outputs['AttributeLoaded'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Attribute Loaded', 'Type'=>'Kernel.Data.Primitive.Boolean', 'Required'=>true));
		$this->outputs['AttributeNotLoaded'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Attribute Not Loaded', 'Type'=>'Kernel.Data.Primitive.Boolean', 'Required'=>true));
	}

	public function runTask(){
	
		if(!parent::runTask()){
			return false;
		}
		//Defaults

		//Load Inputs
		$Entity = $this->getTaskInput('Entity');
		$AttributeName = $this->getTaskInput('AttributeName');

		//code here
		$value = $Entity->getValue($AttributeName->getValue());
		
		if($value){
			$this->setTaskOutput('AttributeValue', $value);
			$this->setTaskOutput('AttributeLoaded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
			$this->setTaskOutput('AttributeNotLoaded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));	
		}else{
			$this->setTaskOutput('AttributeLoaded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));
			$this->setTaskOutput('AttributeNotLoaded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
		}
		

		return $this->completeTask();
	}
}
?>