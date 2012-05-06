<?php
class KernelActionsAPIValidateToken extends KernelObject{
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->useDefinition('Object.Action');
		
		$this->setValue('ID','Kernel.Actions.API.ValidateToken');
		$this->setValue('Name','Validate API Token');
		$this->setValue('Description', 'Validates an API Token');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
		
		$this->addAttribute('Session', 'API.Session', true);
		$this->addAttribute('Token', array('Object.String', 'API.Token'), true);
		
		$this->addEvent('TokenValid');
		$this->addEvent('TokenInvalid');
		$this->addEvent('TokenNotFound');
	}
	
	public function run(&$inputObject){
		
		if(!parent::beforeRun($inputObject)){
			return false;
		}
		
		$sessionObject = $this->getValue('Session');
		if(!$sessionObject){
			$this->addError('No Session Supplied');
			$this->fireEvent('TokenInvalid');
		}else{
			$token = $this->getValue('Token');
		
			$tokenID = '';
			
			if($token instanceof KernelObject){
				$tokenID = $token->getValue('ID');
			}else{
				$tokenID = $token;
			}
			
			if(!$token){
				$this->addError('No Token Supplied');
				$tokenFound = false;
			}else{
				$sessionTokens = $sessionObject->getValue('Tokens');
				$tokenFound = false;
				foreach($sessionTokens as $sessionToken){
					$sessionTokenID = $sessionToken->getValue('ID');
					if($sessionTokenID = $tokenID){
						$tokenFound = true;	
					}
				}
			}
			
			if($tokenFound){
				$this->fireEvent('TokenValid');
			}else{
				$this->fireEvent('TokenNotFound');
			}
		}
		
		
		return parent::afterRun($inputObject);
	}
}
?>