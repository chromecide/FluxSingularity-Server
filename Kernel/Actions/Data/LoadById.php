<?php
class KernelActionsDataLoadById extends KernelObject{
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->useDefinition('Object.Action');
		
		$this->setValue('ID','Kernel.Actions.Data.LoadById');
		$this->setValue('Name','Load By Id');
		$this->setValue('Description', 'Loads an Object By Id');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
		
		$this->addAttribute('ObjectID', array('Object.String'), true, false);
		$this->addAttribute('Object', array('Object'), true, false);
		
		$this->addEvent('ObjectLoaded');
		$this->addEvent('ObjectNotLoaded');
	}
	
	public function run(&$inputObject){
		if(!parent::beforeRun($inputObject)){
			return false;
		}
		
		$erorred = false;
		
		$objectID = $this->getValue('ObjectID');
		$object = new KernelObject();
		if($object->loadById($objectID)){
			$this->setValue('Object', $object);
			$this->fireEvent('ObjectNotLoaded');	
		}else{
			$this->fireEvent('ObjectLoaded');
		}
		
		return parent::afterRun($inputObject);
	}
}
?>