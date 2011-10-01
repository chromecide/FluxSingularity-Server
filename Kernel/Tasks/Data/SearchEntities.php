<?php
 
class KernelTasksDataSearchEntities extends KernelTasksTask{
	public function __construct($data){
		
		$this->_ClassName = 'Kernel.Tasks.Data.SearchEntities';
		$this->_ClassTitle='Search Entities';
		$this->_ClassDescription = 'Search Entities using the supplied Conditions';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.8.0';
		
		$this->inputs['Store'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Store', 'Type'=>'Kernel.Data.DatStore', 'Required'=>true));
		$this->inputs['Entity'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Entity Name', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true));
		$this->inputs['Conditions'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Search Conditions', 'Type'=>'Kernel.Data.Primitive.ConditionGroup', 'Required'=>true));
		
		$this->outputs['Entities'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Entity List', 'Type'=>'Kernel.Data.Entity', 'Required'=>true, 'AllowList'=>true));
	}
	
	public function runTask(){
		$entities = $this->getTaskInput('Entity');
		$conditions = $this->getTaskInput('Conditions');

		if(!$entities instanceof KernelDataPrimitiveList){
			$entity = $entities;
			$entities = DataClassLoader::createInstance('Kernel.Data.Primitive.List');
			$entities->addItem($entity);
		}
		
		$entityCount = $entities->Count();
		$returnList = DataClassLoader::createInstance('Kernel.Data.Primitive.List');
		
		for($i=0;$i<$entityCount;$i++){
			$entity = $entities->getItem($i);
			$entity = DataClassLoader::createInstance($entity->get());
			$items = $entity->find($conditions);
			$itemCount = $items->Count();
			for($x=0;$x<$itemCount;$x++){
				$item = $items->getItem($x);
				$returnList->addItem($item);
			}
		}
		
		$this->setTaskOutput('Entities', $returnList);
	}
}

?>