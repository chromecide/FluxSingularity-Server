<?php
class ModulesAPIActionsStartSession extends KernelObject{
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->useDefinition('Object.Action');
		
		$this->setValue('ID','Modules.API.Actions.StartSession');
		$this->setValue('Name','Start API Session');
		$this->setValue('Description', 'Starts an API Session');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
		
		$this->addAttribute('Client', array('Object.String', 'Modules.API.Client'), true);
		$this->addAttribute('RemoteAddress', 'Object.String', true);
		$this->addAttribute('User', 'Object.Security.User', true);
		
		$this->addEvent('SessionStarted');
		$this->addEvent('SessionNotStarted');
	}
	
	public function run(&$inputObject){
		if(!parent::beforeRun($inputObject)){
			return false;
		}
		
		$client = $this->getValue('Client');
		$remoteAddress = $this->getValue('RemoteAddress');
		$user = $this->getValue('User');
		
		if(!$client){
			$this->addError('No Client Supplied');
			$this->fireEvent('SessionNotStarted');
		}else{
			if(!$remoteAddress){
				$this->addError('No Remote Address Supplied');
				$this->fireEvent('SessionNotStarted');
			}else{
				if(!$user){
					$this->addError('No User Supplied');
					$this->fireEvent('SessionNotStarted');
				}else{
					$sessionObject = new KernelObject('Modules.API.Session');
					$sessionObject->setValue('Name', 'API Session');
					$sessionObject->setValue('Description', 'API Session');
					$sessionObject->setValue('Author', 'Kernel.Actions.API.StartSession');
					$sessionObject->setValue('Client', $client);
					$sessionObject->setValue('RemoteAddress', $remoteAddress);
					$sessionObject->setValue('User', $user);
					$sessionObject->setValue('LastAccessed', strtotime(date('Y-m-d H:i:s')));
					
					if($sessionObject->save()){
						$_SESSION['APISessionID']=$sessionObject->getValue('ID');
						$this->setValue('Session', $sessionObject);
						$this->fireEvent('SessionStarted');
					}else{
						$this->addError('Could not save Session Object', $sessionObject);
						$this->fireEvent('SessionNotStarted');
					}
				}
			}
		}
		
		return parent::afterRun($inputObject);
	}
}
?>