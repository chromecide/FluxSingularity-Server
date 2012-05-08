<?php
class KernelActionsObjectToJSON extends KernelObject{
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->useDefinition('Object.Action');
		
		$this->setValue('ID','Kernel.Actions.ObjectToJSON');
		$this->setValue('Name','Object To JSON');
		$this->setValue('Description', 'Converts a Flux Singularity Object into a JSON Formatted string');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
		
		$this->addAttribute('OutputObject', 'Object');
	}
	
	public function run(&$inputObject){
		if(!parent::beforeRun($inputObject)){
			return false;
		}
		if($this->getValue('OutputObject')){
			
			fb('Using output object');
			$outputObject = $this->getValue('OutputObject');
		}else{
			fb('Using intput object');
			$outputObject = $inputObject;
		}
		
		if(is_array($outputObject)){
			$outputArray = array();
			foreach($outputObject as $inputItem){
				$outputArray[] = $inputItem->toArray(true, true, true, true, true, true, true);
			}
			echo json_encode($outputArray);
		}else{
			
			echo $outputObject->toJSON(true, true, true, true, true, true, true);
		}
		return parent::afterRun($inputObject);
	}
}
?>