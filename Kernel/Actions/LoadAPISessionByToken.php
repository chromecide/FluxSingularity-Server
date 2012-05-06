<?php
class KernelActionsLoadAPISessionByToken extends KernelObject{
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->useDefinition('Object.Action');
		
		$this->setValue('ID','Kernel.Actions.LoadAPISessionByToken');
		$this->setValue('Name','Load API Session by Token');
		$this->setValue('Description', 'Loads an API Session using the supplied Token.  The Token must exist in the Session\'s Tokens Collection');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
		
		$this->addAttribute('Session', 'API.Session');
		$this->addEvent('SessionLoaded');
		$this->addEvent('SessionNotLoaded');
	}
	
	public function run(&$inputObject){
		if(!parent::beforeRun($inputObject)){
			return false;
		}
		
		$tokenID = $inputObject->getValue('Token');
		if($tokenID!=''){
			$query = new KernelObject('Object.Query');
			$query->fromArray(array(
				'Data'=>array(
					'Conditions'=>array(
						array(
							'Data'=>array(
								'Attribute'=>'Definitions',
								'Operator'=>'==',
								'Value'=>'API.Session'
							)
						),
						array(
							'Data'=>array(
								'Attribute'=>'Data.Tokens.ID',
								'Operator'=>'==',
								'Value'=>$tokenID
							)
						)
					)
				)
			));
			
			if($query->find($query)){
				$results = $query->getValue('Results');
				if(count($results)==1){
					$sessionObject = $results[0];
					$this->setValue('Session', $sessionObject);
					$inputObject->setValue('Session', $sessionObject);
					$continue = $this->fireEvent('SessionLoaded');
					
				}else{
					$inputObject->addError('Token Data Error.  Too many tokens.');
					$continue = $this->fireEvent('SessionNotLoaded');
				}
			}
			
		}else{
			$inputObject->addError('No API Token Supplied', null);
			$continue = $this->fireEvent('SessionNotLoaded');
		}
		
		return parent::afterRun($inputObject);
	}
}
?>