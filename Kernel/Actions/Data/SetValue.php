<?php
class KernelActionsDataSetValue extends KernelObject{
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->useDefinition('Object.Action');
		
		$this->setValue('ID','Kernel.Actions.Data.SetValue');
		$this->setValue('Name','Set Object Value');
		$this->setValue('Description', 'Sets the Value of an Object');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
		
		$this->addAttribute('Object', 'Object', true, true);
		$this->addAttribute('AttributeName', 'Object.String', true, false);
		$this->addAttribute('AttributeValue', 'Object', false, false);
		
		$this->addEvent('ValueSet');
		$this->addEvent('ValueNotSet');
		$this->addEvent('InvalidValue');
	}
	
	public function run(&$inputObject){
		if(!parent::beforeRun($inputObject)){
			return false;
		}
		
		$errorred = false;
		
		$object = $this->getValue('Object');
		$attributeName = $this->getValue('AttributeName');
		$attributeValue = $this->getValue('AttributeValue');
		
		$error = !($object->setValue($attributeName, $attributeValue)); 
		
		if($errorred){
			$this->fireEvent('ValueNotSet');
		}else{
			$this->fireEvent('ValueSet');	
		}
		
		return parent::afterRun($inputObject);
	}
}
?>