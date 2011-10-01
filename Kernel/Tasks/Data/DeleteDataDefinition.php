<?php
class KernelTasksDataDeleteDataDefinition extends KernelTasksTask{
	public function __construct(){
		parent::__construct();
		
		$this->_ClassName = 'Kernel.Tasks.Data.DeleteDataDefinition';
		$this->_ClassTitle='Remove a Data Definition';
		$this->_ClassDescription = 'This task will remove a data definition and all entity instances based on the data definition';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.4.0';
		
		//Inputs
		$this->inputs['Store'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Store', 'Type'=>'Kernel.Data.DatStore', 'Required'=>true));
		$this->inputs['Definition'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Definition', 'Type'=>'Kernel.Data.Entity.Definition', 'Required'=>true));
		
		//Outputs
		$this->outputs['DefinitionDeleted'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Definition Deleted', 'Type'=>'Kernel.Data.Primitive.Boolean'));
		$this->outputs['DefinitionNotDeleted'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Definition Not Deleted', 'Type'=>'Kernel.Data.Primitive.Boolean'));
	}

	public function runTask(){
		if(!parent::runTask()){
			return false;
		}
		//Load Inputs
		$TypeString = $this->getTaskInput('TypeString');
		$config = $this->getTaskInput('Values');
		
		//create entity item
		$entity = DataClassLoader::createInstance($TypeString->getValue(), $config);
		
		if($entity->remove()){
			$this->setTaskOutput('DefinitionDeleted', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
			$this->setTaskOutput('DefinitionNotDeleted', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));
		}else{
			//TODO: need to deal with errors
			
			$this->setTaskOutput('DefinitionDeleted', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));
			$this->setTaskOutput('DefinitionNotDeleted', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
		}
		return $this->completeTask();
	}
}
?>