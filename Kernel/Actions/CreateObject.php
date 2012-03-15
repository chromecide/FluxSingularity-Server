<?php
class KernelActionsCreateObject extends KernelObject{
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->addDefinition('Kernel.Action');
		
		$this->setObjectName('Kernel.Action.CreateObject');
		$this->setObjectDescription('Create a Data Object');
		$this->setObjectAuthor('Justin Pradier');
		$this->setObjectVersion('1.0.0');
		
		$this->addField('ObjectName', 'Kernel.Object.String', true, false);
		$this->addField('ObjectDescription', 'Kernel.Object.String', true, false);
		$this->addField('ObjectAuthor', 'Kernel.Object.String', true, false);
		$this->addField('ObjectVersion', 'Kernel.Object.String', true, false);
		$this->addField('ObjectDefinitions', 'Kernel.Object.String', true, true);
	}
	
	public function run(){
		$errored = false;
		//build the object Configuration
		$objectDefinitions = $this->getValue('DefinitionList');
		
		//create the object
		$returnObject = new KernelObject();
		
		//validate
		if(!is_array($objectDefinitions)){
			
		}
		
		//set output
		$this->setValue('Output', $returnObject);
		
		return !$errored;
	}
}
?>