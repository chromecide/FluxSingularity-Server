<?php
class ModulesJSONActionsToJSON extends KernelObject{
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->useDefinition('Object.Action');
		
		$this->setValue('ID','Modules.JSON.Actions.ToJSON');
		$this->setValue('Name','Modules.JSON.Actions.ToJSON');
		$this->setValue('Description', 'Convert an Object to a JSON String');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
		
		$this->addAttribute('ObjectToConvert', array('Object'), true, false, true);
		$this->addAttribute('JSONString', array('Object.String'));
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