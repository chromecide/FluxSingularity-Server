<?php
class ModulesAPIActionsValidateClient extends KernelObject{
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->useDefinition('Object.Action');
		
		$this->setValue('ID','Modules.API.Actions.ValidateClient');
		$this->setValue('Name','Validate API Client');
		$this->setValue('Description', 'Validates an API Client');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
		
		$this->addAttribute('ClientID', 'Object.String', true);
		$this->addAttribute('Client', 'Modules.API.Client', false);
		
		$this->addEvent('ClientNotFound');
		$this->addEvent('ClientValid');
		$this->addEvent('ClientNotValid');
	}
	
	public function run(&$inputObject){
		if(!parent::beforeRun($inputObject)){
			return false;
		}
		
		$clientId = $this->getValue('ClientID');
		
		if($clientId){
			$clientObject = new KernelObject('Modules.API.Client');
			if($clientObject->load($clientId)){
				$this->setValue('Client', $clientObject);
				$this->fireEvent('ClientValid');
			}else{
				$this->addError('Could not find Client');
				$this->fireEvent('ClientInvalid');
			}
		}else{
			$this->addError('No Client ID Supplied');
			$this->fireEvent('ClientNotFound');
		}
		
		return parent::afterRun($inputObject);
	}
}
?>