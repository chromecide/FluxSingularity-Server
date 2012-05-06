<?php
class KernelActionsSetTraceLevel extends KernelObject{
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->addDefinition('Kernel.Action');
		
		$this->setObjectName('Kernel.Actions.SetTraceLevel');
		$this->setObjectDescription('Set the Trace Level for an Object');
		$this->setObjectAuthor('Justin Pradier');
		$this->setObjectVersion('1.0.0');
		
		$this->addField('Level', 'kernel.Object.Number', true, false, true);
	}
	
	public function run(){
		$object = $this->getValue('InputObject');
		$level = $this->getValue('Level');
		$object->setTraceLevel($level);
		
		$this->setValue('OutputObject', $object);
		return false;
	}
}
?>