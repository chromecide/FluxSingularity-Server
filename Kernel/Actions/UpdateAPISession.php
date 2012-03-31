<?php
class KernelActionsUpdateAPISession extends KernelObject{
	
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->useDefinition('Object.Action');
		
		$this->setValue('ID','Kernel.Actions.UpdateAPISession');
		$this->setValue('Name','Kernel.Actions.UpdateAPISession');
		$this->setValue('Description', 'Updates a Kernel API Session');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
		
		$this->addAttribute('Session', 'API.Session');
		$this->addEvent('SessionUpdated');
		$this->addEvent('SessionNotUpdated');
	}
	
	public function run(&$inputObject){
		if(!parent::beforeRun($inputObject)){
			return false;
		}
		
		$inputObject->addTrace('Kernel.Actions.UpdateAPISession', 'Starting Action');
		$newSession = false;
		$succeeded = false;
		
		if($inputObject->usesDefinition('API.Session')){
			$sessionObject = $inputObject;
		}else{
			$sessionObject = $inputObject->getValue('Session');
		}
		
		if($sessionObject){
			$sessionObject->setValue('LastAccessed', strtotime(date('Y-m-d H:i:s')));
			$sessionObject->save();
			$this->fireEvent('SessionUpdated');
		}else{
			$inputObject->addError('Could not Update Session', $sessionObject);
			$this->fireEvent('SessionNotUpdated');
		}
		
		return parent::afterRun($inputObject);
	}
}
?>