<?php
class KernelActionsDataRemoveValue extends KernelObject{
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->useDefinition('Object.Action');
		
		$this->setValue('ID','Kernel.Actions.Data.RemoveValue');
		$this->setValue('Name','Remove Value');
		$this->setValue('Description', 'Removes a value item from an Object.  If the Attribute is defined as a list, this will remove the supplied item, otherwise it will set the value to null');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
		
		$this->addAttribute('Object', 'Object.Definition', true, true);
		$this->addAttribute('AttributeName', 'Object.String', true);
		$this->addAttribute('AttributeValue', 'Object');
		
		$this->addEvent('ValueRemoved');
		$this->addEvent('ValueNotRemoved');
	}
	
	public function run(&$inputObject){
		if(!parent::beforeRun($inputObject)){
			return false;
		}
		
		$errorred = false;
		
		$object = $this->getValue('Object');
		$attributeName = $this->getValue('AttributeName');
		$attributeValue = $this->getValue('AttributeValue');

		

		if($errorred){
			$this->fireEvent('ValueNotRemoved');
		}else{
			//$this->setValue('NewObject', $newObject);
			$this->fireEvent('ValueRemoved');	
		}
		
		return parent::afterRun($inputObject);
	}
}
?>