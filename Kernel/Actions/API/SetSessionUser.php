<?php
class KernelActionsAPIValidateSession extends KernelObject{
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->useDefinition('Object.Action');
		
		$this->setValue('ID','Kernel.Actions.API.ValidateSession');
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
			$sessionObject = new KernelObject('API.Session');
			if($sessionObject->load($_SESSION['APISessionID'])){
				$this->setValue('Session', $sessionObject);
				$this->fireEvent('SessionValid');
			}else{
				$this->fireEvent('SessionInvalid');
			}
		}else{
			$this->fireEvent('SessionNotFound');
		}
		
		return parent::afterRun($inputObject);
	}
}
?>