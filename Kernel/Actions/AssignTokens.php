<?php
class KernelActionsAssignTokens extends KernelObject{
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->useDefinition('Object.Action');
		
		$this->setValue('ID','Kernel.Action.AssignTokens');
		$this->setValue('Name','Assign Tokens');
		$this->setValue('Description', 'Assigns Tokens to an API Session');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
		
		$this->addAttribute('Session', 'API.Session', false, false, true);
		$this->addAttribute('TokenCount', 'Object.Number', true, false);
		$this->addAttribute('Mode', 'Object.String', true, false, true);
		$this->addAttribute('AllowedActions', 'Object.Action', false, true, true, 'Allowed Actions');
		$this->setValue('TokenCount', 1);
		$this->setValue('Mode', 'R');
	}
	
	public function run(&$inputObject){
		
		if(!parent::beforeRun($inputObject)){
			return false;
		}
		
		if($inputObject->usesDefinition('API.Session')){
			$sessionObject = $inputObject;
		}else{
			$sessionObject = $this->getValue('Session');
		}
		
		if($sessionObject && $sessionObject->usesDefinition('API.Session')){
			$tokenCount = $this->getValue('TokenCount');
			$mode = $this->getValue('Mode');
			$allowedActions = $this->getValue('AllowedActions');
			
			for($i=0;$i<$tokenCount;$i++){
				$token = new KernelObject('API.Token');
				$token->setValue('Mode', $mode);
				
				if($allowedActions){
					$token->setValue('AllowedActions', $allowedActions);
				}
				$token->save();
				
				$sessionObject->addValue('Tokens', $token);
			}
			$sessionObject->save();
			fb('Session contains'. count($sessionObject->getValue('Tokens')));
			//fb($sessionObject);
		}
		
		return parent::afterRun($inputObject);
	}
}
?>