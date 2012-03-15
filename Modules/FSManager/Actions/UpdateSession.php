<?php
class ModulesFSManagerActionsUpdateSession extends KernelObject{
	
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->useDefinition('Object.Action');
		
		$this->setValue('ID','Modules.FSManager.Actions.UpdateSession');
		$this->setValue('Name','Modules.FSManager.Actions.UpdateSession');
		$this->setValue('Description', 'Updates an FSManager Session');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
	}
	
	public function run(&$inputObject){
		$newSession = false;
		$inputObject->addTrace('Modules.FSManager.Actions.UpdateSession', 'Starting Action');
		
		$sessionObject = new KernelObject('Modules.FSManager.Object.Session');
		
		if(array_key_exists('FSManagerSession', $_SESSION)){
			if($_SESSION['FSManagerSession']!=''){
				if($sessionObject->load($_SESSION['FSManagerSession'])){
					if($inputObject->usesDefinition('Modules.FSManager.Object.Message')){
						$inputObject->setValue('Source', $_SESSION['FSManagerSession']);
					}	
				}
			}
		}else{
			$newSession = true;
		}
		$userFound = false;
		if(array_key_exists('FSManager_User_ID', $_SESSION) && $_SESSION['FSManager_User_ID']!=''){
			$user = new KernelObject('Object.Security.User');
			
			if($user->load($_SESSION['FSManager_User_ID'])){
				$userFound = true;
			}
		}
		
		if(!$userFound){
			$user = new KernelObject('Object.Security.User');
			$user->setValue('Username', 'Guest');
			$user->setValue('Password', 'Guest');
			
			if($user->findOne()){
				
				$_SESSION['FSManager_User_ID'] = $user->getValue('ID');
			}
		}

		$sessionID = $sessionObject->getValue('ID');
		
		$sessionObject->setValue('Name', 'Session '.$sessionID);
		$sessionObject->setValue('Description', 'Session '.$sessionID);
		$sessionObject->setValue('Author', 'Modules.FSManager.Actions.UpdateSession');
		
		$sessionObject->setValue('LastAccessed', strtotime(date('Y-m-d H:i:s')));
		
		$sessionObject->setValue('User', $user);
		
		if(!$sessionObject->save()){
			//transfer any errors to the new object
			return false;
		}else{
			$_SESSION['FSManagerSession'] = $sessionObject->getValue('ID');
			
			if($inputObject->usesDefinition('Modules.FSManager.Object.Message')){
				$inputObject->setValue('Source', $_SESSION['FSManagerSession']);
			}

			if($newSession){
				$sessionObject->fireEvent('NewSession');
			}
			
			return true;
		}
	}
}
?>