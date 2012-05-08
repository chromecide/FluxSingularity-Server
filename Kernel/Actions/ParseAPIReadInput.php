<?php
class KernelActionsParseAPIReadInput extends KernelObject{
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->useDefinition('Object.Action');
		
		$this->setValue('ID','Kernel.Actions.ParseAPIReadInput');
		$this->setValue('Name','Parse API.Read Inputs');
		$this->setValue('Description', 'Builds a Query Object from Values submitted to the API Read Script');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
	}
	
	public function run(&$inputObject){
		if(!parent::beforeRun($inputObject)){
			return false;
		}
		
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
		
		return parent::afterRun($inputObject);
	}
}
?>