<?php
class KernelActionsCreateAPISession extends KernelObject{
	
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->useDefinition('Object.Action');
		
		$this->setValue('ID','Kernel.Actions.CreateAPISession');
		$this->setValue('Name','Kernel.Actions.CreateAPISession');
		$this->setValue('Description', 'Creates a New Kernel Session');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
		
		$this->addAttribute('MaxTokenCount', 'Object.Number');
		$this->setValue('MaxTokenCount', 5);
		$this->addAttribute('Session');
	}
	
	public function run(&$inputObject){
		
		if(!parent::beforeRun($inputObject)){
			return false;
		}
		
		$inputObject->addTrace('Kernel.Actions.CreateAPISession', 'Starting Action');
		$succeeded = false;
		
		if($inputObject->usesDefinition('API.Read') || $inputObject->usesDefinition('API.Update') || $inputObject->usesDefinition('API.Remove')){
			
			$user = $inputObject->getValue('User');
			$sessionObject = new KernelObject('API.Session');
			
			$sessionObject->setValue('Name', 'API Session');
			$sessionObject->setValue('Description', 'API Session');
			$sessionObject->setValue('Author', 'Kernel.Actions.CreateAPISession');
			$sessionObject->setValue('Version', '1.0.0');
			
			if(!$user){
				//load the guest user
				$user = new KernelObject('Object.Security.User');
				$user->load(self::$GuestUserId);
				$sessionObject->setValue('User', $user);
			}else{
				$sessionObject->setValue('User', $user);
			}

			$sessionObject->setValue('LastAccessed', strtotime(date('Y-m-d H:i:s')));
			
			if(!$sessionObject->save()){
				$inputObject->addError('Could not create Session', $sessionObject);
				return false;
			}else{
				$_SESSION['APISessionID'] = $sessionObject->getValue('ID');
				$sessionObject->addPermission($_SESSION['APISessionID'], true, true, false, false);
				$sessionObject->save();
				$this->setValue('Session', $sessionObject);
				$inputObject->setValue('Session', $sessionObject);
				$_SESSION['APISessionID'] = $sessionObject->getValue('ID');
			}
			return parent::afterRun($inputObject);
			
		}else{
			return false;
		}
	}
}
?>