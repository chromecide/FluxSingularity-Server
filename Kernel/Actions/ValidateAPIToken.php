<?php
class KernelActionsValidateAPIToken extends KernelObject{
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->useDefinition('Object.Action');
		
		$this->setValue('ID','Kernel.Actions.ValidateAPIToken');
		$this->setValue('Name','Validate API Token');
		$this->setValue('Description', 'Validates an API Token against the Stored Valid Tokens');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
		
		$this->addAttribute('TokenValid', 'Object.Boolean', true);
		$this->setValue('TokenValid', false);
		
	}
	
	public function run(&$inputObject){
		
		if(!parent::beforeRun($inputObject)){
			return false;
		}
		$continue = false;
		
		$session_ID = $_SESSION['APISessionID'];
		if(!$session_ID){
			$inputObject->fireEvent('SessionInvalid');
		}else{
			if($inputObject->usesDefinition('API.Read') || $inputObject->usesDefinition('API.Update') || $inputObject->usesDefinition('API.Remove')){
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
										'Attribute'=>'Data.ID',
										'Operator'=>'==',
										'Value'=>$session_ID
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
							$continue = $inputObject->fireEvent('TokenValid');
						}else{
							$inputObject->addError('Token Data Error.  Too many tokens.');
							$continue = $inputObject->fireEvent('TokenInvalid');
						}
					}else{
						$inputObject->addError('Token not Found');
						$continue = $inputObject->fireEvent('TokenInvalid');
					}
				}else{
					$inputObject->addError('No API Token Supplied', null);
					$continue = $inputObject->fireEvent('TokenInvalid');
				}
			}	
		}
		

		if($continue){
			return parent::afterRun($inputObject);	
		}else{
			return false;
		}
		
	}
}
?>