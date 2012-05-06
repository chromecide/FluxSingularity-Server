<?php
class KernelActionsDataSaveObject extends KernelObject{
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->useDefinition('Object.Action');
		
		$this->setValue('ID','Kernel.Actions.Data.SaveObject');
		$this->setValue('Name','Kernel.Actions.Data.SaveObject');
		$this->setValue('Description', 'Saves an Object');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
		
		$this->addAttribute('ObjectToSave', array('Object'), true, false, true);
		
		$this->addEvent('ObjectSaved');
		$this->addEvent('ObjectNotSaved');
	}
	
	public function notify($eventName, &$inputObject){
		return $this->run($inputObject);
	}
	
	public function run(&$inputObject){
		
		if(!parent::beforeRun($inputObject)){
			return false;
		};
		
		$objectToSave = $this->getValue('ObjectToSave');
		
		if($objectToSave instanceof KernelObject){
			$objectToSave->save();
		}
		
		return parent::afterRun($inputObject);
	}
}
?>