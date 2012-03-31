<?php
class KernelActionsExpireToken extends KernelObject{
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->useDefinition('Object.Action');
		
		$this->setValue('ID','Kernel.Action.ExpireToken');
		$this->setValue('Name','Expire Token');
		$this->setValue('Description', 'Expires a Token so it cannot be reused.');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
		
		$this->addAttribute('TokenID', 'Object.String', false, false);
	}
	
	public function run(&$inputObject){
		if(!parent::beforeRun($inputObject)){
			return false;
		}
		$tokenObject = null;
		
		if(($inputObject instanceof KernelObject) && $inputObject->usesDefinition('API.Token')){
			$tokenObject = $inputObject;
			
		}else{
			
			$tokenID = $this->getValue('TokenID');
			
			if(!$tokenID){
				$inputObject->addError('Invalid Token Supplied 1', $tokenID);
				return false;
			}
			
			$tokenObject = new KernelObject('API.Token');
			
			if(!$tokenObject->load($tokenID)){
				$inputObject->addError('Invalid Token Supplied 2', $inputObject);
				
				return false;
			}
		}
		
		if($tokenObject){
			fb('------ Expiring Token: '.$tokenID);
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
					fb('--------- Removing Reference from: '.$object->getValue('ID'));
					$object->removeReferencedObject($tokenID);
					$object->save();
				}
				
				if(!$tokenObject->remove()){
					$inputObject->addError('Could not Remove Token', $tokenObject);
					return false;
				}	
			}
			
			
		}
		
		return parent::afterRun($inputObject);
	}
}
?>