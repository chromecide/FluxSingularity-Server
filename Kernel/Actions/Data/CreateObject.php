<?php
class KernelActionsDataCreateObject extends KernelObject{
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->useDefinition('Object.Action');
		
		$this->setValue('ID','Kernel.Actions.Data.CreateObject');
		$this->setValue('Name','Create Object');
		$this->setValue('Description', 'Creates an Object');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
		
		$this->addAttribute('DefinitionList', array('Object.String'), true, true);
		$this->addAttribute('AttributeList', array('Object.Attribute'), false, true);
		$this->addAttribute('AttributeValues', array('Object'), false, true);
		$this->addAttribute('NewObject', array('Object'), false, false, true);
		
		$this->addEvent('ObjectCreated');
		$this->addEvent('InvalidDefinition');
		$this->addEvent('ObjectNotCreated');
	}
	
	public function notify($eventName, &$inputObject){
		return $this->run($inputObject);
	}
	
	public function run(&$inputObject){
		if(!parent::beforeRun($inputObject)){
			return false;
		}
		
		$errorred = false;
		
		$newObject = new KernelObject();
		$newObject->useDefinition('Object');
		$definitions = $this->getValue('DefinitionList');
		
		foreach($definitions as $definition){
			$newObject->useDefinition($definition);
		}
		
		$attributeValues = $this->getValue('AttributeValues');
		
		foreach($attributeValues as $attributeName=>$attributeValue){
			if(is_array($attributeValue)){
				if(array_key_exists('Definitions', $attributeValue)){
					$valueModel = $attributeValue;
					$attributeValue = new KernelObject();
					$attributeValue->setModel($valueModel);
					if($attributeValue->usesDefinition('Object.AttributeMap')){
						$newAttributeValue = $this->getValue($attributeValue->getValue('Source'));
					}
				}else{
					$newAttributeValue = $attributeValue;
				}
			}else{
				$attrCfg = $newObject->getAttribute($attributeName);
				
				$newAttributeValue = $attributeValue;//$inputObject->getValue($attributeName);
			}
			
			$newObject->setValue($attributeName, $newAttributeValue);
		}
		
		if($errorred){
			$this->fireEvent('ObjectNotCreated');
		}else{
			$this->setValue('NewObject', $newObject);
			$this->suspendEvents();
			$this->save();
			$this->resumeEvents();
			$this->fireEvent('ObjectCreated');
		}

		return parent::afterRun($inputObject);
	}
}
?>