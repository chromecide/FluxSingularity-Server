<?php
class KernelTasksDataDeleteEntity extends KernelTasksTask{
	public function __construct(){
		parent::__construct();
		
		$this->_ClassName = 'Kernel.Tasks.Data.CreateEntity';
		$this->_ClassTitle='Create an Entity Instance';
		$this->_ClassDescription = 'This task will create an Entity Instance';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.4.0';

		//Inputs
		$this->inputs['Store'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Store', 'Type'=>'Kernel.Data.DatStore', 'Required'=>true));
		$this->inputs['Entity'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Entity', 'Type'=>'Kernel.Data.Entity', 'Required'=>true));
		
		//Outputs
		$this->outputs['EntityDeleted'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Entity Deleted', 'Type'=>'Kernel.Data.Primitive.Boolean'));
		$this->outputs['EntityNotDeleted'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Entity Not Deleted', 'Type'=>'Kernel.Data.Primitive.Boolean'));
	}

	public function runTask(){
	
		if(!parent::runTask()){
			return false;
		}
		
		//Defaults

		//Load Inputs
		$Entity = $this->getTaskInput('Entity');

		//code here
		if($Entity->remove()){
			$this->setTaskOutput('EntityRemoved', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
			$this->setTaskOutput('EntityNotRemoved', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));
		}else{
			$this->setTaskOutput('EntityRemoved', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));
			$this->setTaskOutput('EntityNotRemoved', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
			
			$errorList = DataClassLoader::createInstance('Kernel.Data.Primitive.List');
			
			$error = DataClassLoader::createInstance('Kernel.Data.Primitive.String', 'An Unknown Error Occurred');
			$errorList->addItem($error);
			
			$this->setTaskOutput('Errors', $errorList);
		}

		return $this->completeTask();
	}
}
?>