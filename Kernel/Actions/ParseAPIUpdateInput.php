<?php
class KernelActionsParseAPIUpdateInput extends KernelObject{
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->useDefinition('Object.Action');
		
		$this->setValue('ID','Kernel.Actions.ParseAPIUpdateInput');
		$this->setValue('Name','Parse API.Update Inputs');
		$this->setValue('Description', 'Creates System Objects from data sent for an update');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
	}
	
	public function run(&$inputObject){
		if(!parent::beforeRun($inputObject)){
			return false;
		}
		
		$jsonStr = file_get_contents('php://input');

		if($jsonStr){
			$input = json_decode($jsonStr);
		}
		
		$outputs = array();
		$inputArray = array();
		
		if(is_array($input)){
			$inputArray = $input;
		}else{
			$inputArray[] = $input;
		}
		
		foreach($inputArray as $inputObjectItem){
			$object = new KernelObject();
			
			if(property_exists($inputObjectItem, 'ID')){
				$id = $inputObjectItem->ID;
				if($id){
					$object->load($id);
				}	
			}
			
			$inputObjectItem = (array)$inputObjectItem;
			$object->fromArray($inputObjectItem);
			
			$outputs[] = $object->toArray();
		}
		
		$inputObject->setValue('Objects', $outputs);
		
		return parent::afterRun($inputObject);
	}
}
?>