<?php
class KernelActionsClearActions extends KernelObject{
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->useDefinition('Object.Action');
		
		$this->setValue('ID','Kernel.Action.ClearActions');
		$this->setValue('Name','Clear Actions');
		$this->setValue('Description', 'Clears Action Values from an object.');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
	}
	
	public function run(&$inputObject){
		if(!parent::beforeRun($inputObject)){
			return false;
		}
		
		$inputObject->removeAllActions();
		
		return parent::afterRun($inputObject);
	}
}
?>