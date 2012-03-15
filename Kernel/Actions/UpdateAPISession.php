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
	}
	
	public function run(&$inputObject){
		if(!parent::beforeRun($inputObject)){
			return false;
		}
		
		echo 'updating session<br/>';
		
		$inputObject->addTrace('Kernel.Actions.UpdateAPISession', 'Starting Action');
		$newSession = false;
		$succeeded = false;
		
		$sessionObject = new KernelObject('API.Session');
		if($inputObject->usesDefinition('API.Read') || $inputObject->usesDefinition('API.Read') || $inputObject->usesDefinition('API.Read')){
			if(array_key_exists('API_Session', $_SESSION)){
				if($_SESSION['API_Session']!=''){
					$sessionObject = new KernelObject('API.Session');
					if(!$sessionObject->load($_SESSION['API_Session'])){
						$inputObject->addError('Session Expired', $_SESSION['API_Session']);
						return false;
					}else{
						$sessionObject->setValue('LastAccessed', strtotime(date('Y-m-d H:i:s')));
					}

					if($newSession){
						if(!$inputObject->fireEvent('NewSession')){
							return false;
						}
					}
				}else{
					$inputObject->addError('No Session Found');
					return false;
				}
			}else{
				$inputObject->addError('No Session Found');
				return false;
			}
		}
		
		return parent::afterRun($inputObject);
	}
}
?>