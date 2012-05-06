<?php
class ModulesProcessesActionsRunProcess extends KernelObject{
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->useDefinition('Object.Action');
		
		$this->setValue('ID','Modules.Processes.Actions.RunProcess');
		$this->setValue('Name','Run Process');
		$this->setValue('Description', 'Runs a Process');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
		
		$this->addAttribute('Process', 'Modules.Processes.Process', true, false);
		$this->addAttribute('ProcessState', 'Modules.Processes.State', true, false);
	}
	
	public function run(&$inputObject){
		if(!parent::beforeRun($inputObject)){
			return false;
		}
		
		
		
		return parent::afterRun($inputObject);
	}
}
?>