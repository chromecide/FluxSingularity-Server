<?php
class ModulesHTTPActionsSendResponse extends KernelObject{
	
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->useDefinition('Object.Action');
		
		$this->setValue('ID','Modules.HTTP.Actions.SendResponse');
		$this->setValue('Name','Modules.HTTP.Actions.SendResponse');
		$this->setValue('Description', 'Sends a HTTP Response');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
		
		$this->addAttribute('Content', array('Object.String'), true);
	}
	
	public function notify($eventName, &$inputObject){
		return $this->run($inputObject);
	}
	
	public function run(&$inputObject){
		if(!parent::beforeRun($inputObject)){
			
			return false;
		};
		
		$httpContent = $this->getValue('Content');
		
		echo $httpContent;
		
		return parent::afterRun($inputObject);
	}
}
?>