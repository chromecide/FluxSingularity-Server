<?php
class ModulesJSONActionsFromJSON extends KernelObject{
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->useDefinition('Object.Action');
		
		$this->setValue('ID','Modules.JSON.Actions.FromJSON');
		$this->setValue('Name','Modules.JSON.Actions.FromJSON');
		$this->setValue('Description', 'Convert a JSON Representation of an object into a KernelObject');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
		
		$this->addAttribute('JSONString', array('Object.String'), true);
		$this->addAttribute('Object', array('Object'), false, false, true);
	}
	
	public function notify($eventName, &$inputObject){
		return $this->run($inputObject);
	}
	
	public function run(&$inputObject){
		if(!parent::beforeRun($inputObject)){
			return false;
		};
		
		$objectToConvert = $this->getValue('ObjectToConvert');
		
		if($objectToConvert instanceof KernelObject){
			$this->setValue('JSONString', json_encode($objectToConvert->getModel()));	
		}
		return parent::afterRun($inputObject);
	}
}
?>