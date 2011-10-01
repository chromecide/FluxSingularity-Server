<?php
class KernelTasksDataCreateEntity extends KernelTasksTask{
	public function __construct(){
		parent::__construct();
		
		$this->_ClassName = 'Kernel.Tasks.Data.CreateEntity';
		$this->_ClassTitle='Create an Entity Instance';
		$this->_ClassDescription = 'This task will create an Entity Instance';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.5.0';
		
		//Inputs
		$this->inputs['Type'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Type', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		$this->inputs['Values'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Values', 'Type'=>'Kernel.Data.Primitive.NamedList', 'Required'=>false, 'AllowList'=>false));
		
		//Outputs
		$this->outputs['Entity'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Entity', 'Type'=>'Kernel.Data.Entity'));
	}

	public function runTask(){
		if(!parent::runTask()){
			return false;
		}
		
		//Load Inputs
		$TypeString = $this->getTaskInput('Type');
		$config = $this->getTaskInput('Values');
		
		//create entity item
		$entity = DataClassLoader::createInstance($TypeString->getValue(), $config);
		
		$this->setTaskOutput('Entity', $entity);
		
		return $this->completeTask();
	}
}
?>