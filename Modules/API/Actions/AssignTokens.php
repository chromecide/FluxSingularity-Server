<?php
class ModulesAPIActionsAssignTokens extends KernelObject{
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->useDefinition('Object.Action');
		
		$this->setValue('ID','Modules.API.Actions.AssignTokens');
		$this->setValue('Name','Assign API Tokens');
		$this->setValue('Description', 'Assigns Tokens to an API Session');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
		
		$this->addAttribute('Session', array('Modules.API.Session'), true, false, true);
		$this->addAttribute('TokenCount', array('Object.Number'), true, false);
		$this->addAttribute('Mode', array('Object.String'), true, false, true);
		$this->addAttribute('AllowedActions', array('Object.Action'), false, true, true);
		
		$this->setValue('TokenCount', 1);
		$this->setValue('Mode', 'R');
	}
	
	public function run(&$inputObject){
		
		if(!parent::beforeRun($inputObject)){
			return false;
		}
		
		if($inputObject->usesDefinition('Modules.API.Session')){
			$sessionObject = $inputObject;
		}else{
			$sessionObject = $this->getValue('Session');
			$sessionObject->loadById($sessionObject->getValue('ID'));
		}
		
		if($sessionObject && $sessionObject->usesDefinition('Modules.API.Session')){
			$tokenCount = $this->getValue('TokenCount');
			$mode = $this->getValue('Mode');
			$allowedActions = $this->getValue('AllowedActions');
			
			for($i=0;$i<$tokenCount;$i++){
				$token = new KernelObject();
				$token->useDefinition('Modules.API.Token'); 
				$token->setValue('Mode', $mode);
				
				if($allowedActions){
					$token->setValue('AllowedActions', $allowedActions);
				}
				$token->save();
				
				$sessionObject->addValue('Tokens', $token);
			}
			$sessionObject->save();
		}
		
		return parent::afterRun($inputObject);
	}
}
?>