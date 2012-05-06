<?php
class ModulesAPIActionsParseReadInput extends KernelObject{
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->useDefinition('Object.Action');
		
		$this->setValue('ID','Modules.API.Actions.ParseReadInput');
		$this->setValue('Name','Parse API.Read Inputs');
		$this->setValue('Description', 'Loads the values of an API Read request');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
		
		$this->addAttribute('Request', 'Modules.API.Read');
		$this->addAttribute('Session', 'Modules.API.Session');
		
		$this->addEvent('RequestLoaded');
		$this->addEvent('RequestNotLoaded');
	}
	
	public function run(&$inputObject){
		if(!parent::beforeRun($inputObject)){
			return false;
		}
		
		$readRequest = new KernelObject('Modules.API.Read');
		$readRequest->setValue('Name', 'API Read Request');
		$readRequest->setValue('RemoteAddress', $_SERVER['REMOTE_ADDR']);
		foreach($_REQUEST as $paramName=>$paramValue){
			switch($paramName){
				case 'SessionID':
					$readRequest->setValue('Session', $paramValue);
					break;
				case 'ClientID':
					$readRequest->setValue('Client', $paramValue);			
					break;
				case 'Token':
					$readRequest->setValue('Token', $paramValue);
					break;
				case 'Conditions':
					//create a query object
					$queryObject = new KernelObject('Object.Query');
					$queryObject->setValue('Name', 'API Query');
					$queryObject->setValue('Description', 'API Query Object');
					$queryObject->setValue('Author', 'Kernel.Actions.ParseAPIReadInput');
					$queryObject->setValue('Version', '1.0.0');
					$queryObject->setValue('QueryType', 'AND');
					
					$conditions = json_decode($paramValue);
					foreach($conditions as $idx=>$conditionCfg){
						$queryCondition = new KernelObject('Object.Condition');
						$queryCondition->setValue('Attribute', $conditionCfg->AttributeName);
						$queryCondition->setValue('Operator', $conditionCfg->Operator);
						$queryCondition->setValue('Value', $conditionCfg->Value);
						$queryObject->addValue('Conditions', $queryCondition);
					}
					$readRequest->setValue('Query', $queryObject);
					break;
				case 'Queries':
					//parse the supplied queries
					break;
				default:
					break;
			}
		}

		//override supplied values with object mapped values
		$actionSession = $this->getValue('Session');
		if($actionSession){
			$readRequest->setValue('Session', $actionSession);
		}
		
		// //fb($readRequest);
		$this->setValue('Request', $readRequest);
		$this->fireEvent('RequestLoaded');
		/*
		$queryObject = new KernelObject('Object.Query');
		$queryObject->setValue('Name', 'API Query');
		$queryObject->setValue('Description', 'API Query Object');
		$queryObject->setValue('Author', 'Kernel.Actions.ParseAPIReadInput');
		$queryObject->setValue('Version', '1.0.0');
		$queryObject->setValue('QueryType', 'AND');
		
		foreach($_REQUEST as $paramName=>$paramValue){
			if($paramName!='_dc' && $paramName!='limit' && $paramName!='page' && $paramName!='start'){
				switch($paramName){
					case 'Token':
					case 'token':
						$inputObject->setValue('Token', $paramValue);
						break;
					case 'Conditions':
						$conditions = json_decode($paramValue);
						foreach($conditions as $idx=>$conditionCfg){
							$queryCondition = new KernelObject('Object.Condition');
							$queryCondition->setValue('Attribute', $conditionCfg->AttributeName);
							$queryCondition->setValue('Operator', $conditionCfg->Operator);
							$queryCondition->setValue('Value', $conditionCfg->Value);
							$queryObject->addValue('Conditions', $queryCondition);
						}
						
						break;
					default:
						
						$queryCondition = new KernelObject('Object.Condition');
						$queryCondition->setValue('Attribute', $paramName);
						
						if(json_decode($paramValue)){
							
						}else{
							$queryCondition->setValue('Operator', '==');
							$queryCondition->setValue('Value', $paramValue);
						}

						$queryObject->addValue('Conditions', $queryCondition);
						break;
				}
			}
		}
		
		//add security
		$sessionCondition = new KernelObject('Object.Condition');
		$sessionCondition->setValue('Attribute', 'Permissions.Data.Recipient.ID');
		$sessionCondition->setValue('Operator', '==');
		$sessionCondition->setValue('Value', array_key_exists('APISessionID', $_SESSION)?$_SESSION['APISessionID']:'');
		
		$everyoneCondition = new KernelObject('Object.Condition');
		$everyoneCondition->setValue('Attribute', 'Permissions');
		$everyoneCondition->setValue('Operator', '==');
		$everyoneCondition->setValue('Value', '[]');
		
		$securityQuery = new KernelObject('Object.Query');
		$securityQuery->setValue('QueryType', 'OR');
		$securityQuery->addValue('Conditions', $sessionCondition);
		$securityQuery->addValue('Conditions', $everyoneCondition);
		
		$queryObject->addValue('Conditions', $securityQuery);
		$inputObject->setValue('Query', $queryObject);
		*/
		return parent::afterRun($inputObject);
	}
}
?>