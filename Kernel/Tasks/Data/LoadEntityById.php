<?php 
class KernelTasksDataLoadEntityById extends KernelTasksTask{
	public function __construct($data){
		parent::__construct($data);
		
		$this->_ClassName = 'Kernel.Tasks.Data.LoadEntityById';
		$this->_ClassTitle='Load Entity By ID';
		$this->_ClassDescription = 'Loads an Entity using it\'s unique ID';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.5.0';
		
		$this->inputs['EntityType'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Entity Type', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true));
		$this->inputs['EntityID'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Entity ID', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true));
		
		$this->outputs['Entity'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Entity', 'Type'=>'Kernel.Data.Entity', 'Required'=>true));
		$this->outputs['EntityLoaded'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Entity Loaded', 'Type'=>'Kernel.Data.Primitive.Boolean', 'Required'=>true));
		$this->outputs['EntityNotLoaded'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Entity Not Loaded', 'Type'=>'Kernel.Data.Primitive.Boolean', 'Required'=>true));
		
	}
	
	public function runTask(){
		if(!parent::runTask()){
			return false;
		}
		$type = $this->getTaskInput('EntityType')->getValue();
		$id = $this->getTaskInput('EntityID');
		
		$entity = DataClassLoader::createInstance($type);
		if($entity->loadById($id)){
			$this->setTaskOutput('Entity', $entity);
			$this->setTaskOutput('EntityLoaded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
			$this->setTaskOutput('EntityNotLoaded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));
		}else{
			$this->setTaskOutput('EntityLoaded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));
			$this->setTaskOutput('EntityNotLoaded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
		}
		
		return $this->completeTask();
	}
}
?>