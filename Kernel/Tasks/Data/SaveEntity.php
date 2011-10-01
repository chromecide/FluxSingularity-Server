<?php 
class KernelTasksDataSaveEntity extends KernelTasksTask{
	public function __construct(){
		parent::__construct();
		
		$this->_ClassName = 'Kernel.Tasks.Data.SaveEntity';
		$this->_ClassTitle='Save an Entity Instance';
		$this->_ClassDescription = 'Saves an Entity to the supplied Data Store';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.8.0';
		
		$this->inputs['Store'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Store', 'Type'=>'Kernel.Data.DatStore', 'Required'=>true));
		$this->inputs['Entity'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Entity', 'Type'=>'Kernel.Data.Entity', 'Required'=>true, 'AllowList'=>false));
		
		
		$this->outputs['Entity'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Entity', 'Type'=>'Kernel.Data.Entity', 'Required'=>true));
		$this->outputs['EntitySaved'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Entity Saved', 'Type'=>'Kernel.Data.Primitive.Boolean', 'Required'=>true));
		$this->outputs['EntityNotSaved'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Entity Saved', 'Type'=>'Kernel.Data.Primitive.Boolean', 'Required'=>true));
	}
	
	public function runTask(){
		if(!parent::runTask()){
			return false;
		}
		
		$entity = $this->getTaskInput('Entity');
		
		if($entity){
			if(!$newID = $entity->save()){
				$errors = DataClassLoader::createInstance('Kernel.Data.Primitive.List');
			
				$errors->setValue('Kernel.Data.Primitive.String');
				
				foreach($entity->validationErrors as $error){
					$errors->addItem(new KernelDataPrimitiveString($error[0].' - '.$error[1]));
				}
				
				$this->setTaskOutput('Entity', $entity);
				$this->setTaskOutput('Errors', $errors);
				$saved = false;
			}else{
				$entity->set('KernelID', new KernelDataPrimitiveString($newID));
				
				$this->setTaskOutput('Entity', $entity);		
				$saved = true;
			}
		}
		
		$this->setTaskOutput('Saved', new KernelDataPrimitiveBoolean($saved));
		$this->setTaskOutput('NotSaved', new KernelDataPrimitiveBoolean(!$saved));
		
		$this->setTaskOutput('Completed', new KernelDataPrimitiveBoolean(true));
		
		return true;
	}
}
?>