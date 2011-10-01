<?php 

class ModulesFSManagerEventsSessionInitialised extends KernelEventsEvent{
	public function __construct($data){
		parent::__construct($data);
		
		$this->outputs['Session'] = array('Modules.FSManager.Data.Session', true, false);
	}
	
	public function fire(){
		parent::fire();
	}
}

?>