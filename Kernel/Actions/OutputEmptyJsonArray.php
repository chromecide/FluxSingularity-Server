<?php
class KernelActionsOutputEmptyJsonArray extends KernelObject{
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->useDefinition('Object.Action');
		
		$this->setValue('ID','Kernel.Actions.OutputEmptyJsonArray');
		$this->setValue('Name','Output Empty JSON Array');
		$this->setValue('Description', 'Outputs an Empty JSON Array');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
	}
	
	public function run(&$inputObject){
		if(!parent::beforeRun($inputObject)){
			return false;
		}
		
		echo '[]';
		return parent::afterRun(&$inputObject);
	}
}
?>