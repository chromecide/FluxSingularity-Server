<?php
class KernelActionsFireEvent extends KernelObject{
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->addDefinition('Kernel.Action');
		
		$this->setObjectName('Kernel.Action.FireEvent');
		$this->setObjectDescription('Fires an Event on an Object');
		$this->setObjectAuthor('Justin Pradier');
		$this->setObjectVersion('1.0.0');
		
		$this->addField('EventName', 'Kernel.Object.String', true, false);
	}
	
	public function run(){
		
		return false;
	}
}
?>