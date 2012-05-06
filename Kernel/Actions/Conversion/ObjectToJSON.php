<?php
class KernelActionsConversionObjectToJSON extends KernelObject{
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->useDefinition('Object.Action');
		
		$this->setValue('ID','Kernel.Actions.Conversion.ObjectToJSON');
		$this->setValue('Name','Object To JSON');
		$this->setValue('Description', 'Converts a Flux Singularity Object into a JSON Formatted string');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
		
		$this->addAttribute('OutputObject', 'Object', true);
		$this->addAttribute('JSONString', 'Object.String');
	}
	
	public function run(&$inputObject){
		if(!parent::beforeRun($inputObject)){
			return false;
		}
		
		if($this->getValue('OutputObject')){
			$outputObject = $this->getValue('OutputObject');
		}else{
			$outputObject = $inputObject;
		}
		
		
		if(is_array($outputObject)){
			$outputArray = array();
			foreach($outputObject as $inputItem){
				$outputArray[] = $inputItem->toArray(true, true, true, true, true, true, true);
			}
			$jsonString = json_encode($outputArray);
			$this->setValue('JSONString', $jsonString);
		}else{
			
			$jsonString = $outputObject->toJSON(true, true, true, true, true, true, true);
			
			$this->setValue('JSONString', $jsonString);
		}
		echo $jsonString;
		return parent::afterRun($inputObject);
	}
}
?>