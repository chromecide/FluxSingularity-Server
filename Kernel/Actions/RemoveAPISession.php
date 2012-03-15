<?php
class KernelActionsRemoveAPISession extends KernelObject{
	
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->useDefinition('Object.Action');
		
		$this->setValue('ID','Kernel.Actions.RemoveAPISession');
		$this->setValue('Name','Kernel.Actions.RemoveAPISession');
		$this->setValue('Description', 'Removes an API Session');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
	}
	
	public function run(&$inputObject){
		$inputObject->addTrace('Kernel.Actions.RemoveAPISession', 'Starting Action');
		$succeeded = false;
		if(array_key_exists('API_Session', $_SESSION)){
			if($_SESSION['API_Session']!=''){
				$sessionObject = new KernelObject('API.Session');
				if(!$sessionObject->load($_SESSION['API_Session'])){
					$inputObject->addError('Session Expired', $_SESSION['API_Session']);
					return false;
				}else{
					if(!$sessionObject->remove()){
						$inputObject->addError('Could not Remove Session', $sessionObject);
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
			
		return true;
	}
}
?>