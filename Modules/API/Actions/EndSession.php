<?php
class ModulesAPIActionsEndSession extends KernelObject{
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->useDefinition('Object.Action');
		
		$this->setValue('ID','Modules.API.ActionsEndSession');
		$this->setValue('Name','End API Session');
		$this->setValue('Description', 'Ends an API Session');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
		
		$this->addAttribute('Session', array('Object'));
		
		$this->addEvent('SessionEnded');
		$this->addEvent('SessionNotEnded');
		$this->addEvent('SessionNotFound');
	}
	
	public function run(&$inputObject){
		if(!parent::beforeRun($inputObject)){
			return false;
		}
		
		$sessionObject = $this->getValue('Session');
		
		if(!$sessionObject){
			session_destroy();
			$this->fireEvent('SessionNotFound');
		}else{
			if($sessionObject->remove()){
				session_destroy();
				$this->fireEvent('SessionEnded');
			}else{
				$this->addError('Could not remove Session Object');
				$this->fireEvent('SessionNotEnded', $sessionObject);
			}
			
		}
		
		return parent::afterRun($inputObject);
	}
}
?>