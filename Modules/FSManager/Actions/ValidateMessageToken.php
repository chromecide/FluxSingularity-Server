<?php
class ModulesFSManagerActionsValidateMessageToken extends KernelObject{
	
	public function __construct($cfg=null){
		parent::__construct($cfg);
		$this->setValue('ID', 'Modules.FSManager.Actions.ValidateMessageToken');
		$this->setValue('Name', 'Modules.FSManager.Actions.ValidateMessageToken');
		$this->setValue('Description', 'Validate a Message Token');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
	}
	
	public function run(&$inputObject){
		$inputObject->addTrace('Modules.FSManager.Actions.ValidateMessageToken', 'Starting Action');
		
		//give the session access to the guest viewport
		if($inputObject->usesDefinition('Modules.FSManager.Object.Message')){
			$token = $inputObject->getValue('Token');
			if($token!=''){
				
			}else{
				$inputObject->addError('Invalid Message Token');
				return false;	
			}
		}else{
			return false;
		}
	}
	
	
}
?>