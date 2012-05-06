<?php
class ModulesAPIActionsValidateSession extends KernelObject{
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->useDefinition('Object.Action');
		
		$this->setValue('ID','Modules.API.Actions.ValidateSession');
		$this->setValue('Name','Validate API Session');
		$this->setValue('Description', 'Validates an API Session');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
		
		$this->addEvent('SessionValid');
		$this->addEvent('SessionInvalid');
		$this->addEvent('SessionNotFound');
	}
	
	public function run(&$inputObject){
		if(!parent::beforeRun($inputObject)){
			return false;
		}
		
		if(array_key_exists('APISessionID', $_SESSION)){
			//load the session object
			$sessionObject = new KernelObject('Modules.API.Session');
			if($sessionObject->load($_SESSION['APISessionID'])){
				$this->setValue('Session', $sessionObject);
				$inputObject->setValue('Session', $sessionObject);
				$this->fireEvent('SessionValid');
			}else{
				$this->addError('Could not Find Session: '.$_SESSION['APISessionID']);
				$this->fireEvent('SessionInvalid');
			}
		}else{
			$this->fireEvent('SessionNotFound');
		}
		
		return parent::afterRun($inputObject);
	}
}
?>