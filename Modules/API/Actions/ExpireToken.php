<?php
class ModulesAPIActionsExpireToken extends KernelObject{
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->useDefinition('Object.Action');
		
		$this->setValue('ID','Modules.API.Actions.ExpireToken');
		$this->setValue('Name','Expire API Token');
		$this->setValue('Description', 'Expires a Token so it cannot be reused.');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
		
		$this->addAttribute('Session', array('Object.String', 'Modules.API.Session'), true, false);
		$this->addAttribute('Token', array('Object.String', 'Modules.API.Token'), true, false);
	}
	
	public function run(&$inputObject){
		if(!parent::beforeRun($inputObject)){
			return false;
		}
		
		$tokenID = false;
		$session = $this->getValue('Session');
		
		$token = $this->getValue('Token');
		$tokenObject = null;
		
		if($token instanceof KernelObject){
			$tokenObject = $token;
			$tokenID = $token->getValue('ID');
		}else{
			$tokenID = $token;
			$tokenObject = new KernelObject();
			$tokenObject->loadById($tokenID);
		}
		
		if($tokenObject){
			//fb('------ Expiring Token: '.$tokenID);
			//remove any references to this object
			$query = new KernelObject('Object.Query');
			$condition = new KernelObject('Object.Condition');
			$condition->setValue('Attribute', 'ReferencedObjects.ID');
			$condition->setValue('Operator', '==');
			$condition->setValue('Value', $tokenID);
			$query->addValue('Conditions', $condition);
			if($query->find()){
				$results = $query->getValue('Results');
				foreach($results as &$object){
					$object->removeReferencedObject($tokenID);
					$object->save();
				}
				
				if(!$tokenObject->remove()){
					$inputObject->addError('Could not Remove Token', $tokenObject);
					return false;
				}

				if($session){
					$session->removeReferencedObject($tokenID);
				}
			}
		}
		
		return parent::afterRun($inputObject);
	}
}
?>