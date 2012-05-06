<?php
class KernelActionsSaveObjects extends KernelObject{
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->useDefinition('Object.Action');
		
		$this->setValue('ID','Kernel.Actions.SaveObjects');
		$this->setValue('Name','Save Objects');
		$this->setValue('Description', 'Saves Objects');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
	}
	
	public function run(&$inputObject){
		if(!parent::beforeRun($inputObject)){
			return false;
		}
		
		$objects = $inputObject->getValue('Objects');
		print_r($objects);
		
		$inputObject->removeAllActions();
		
		return parent::afterRun($inputObject);
	}
}
?>