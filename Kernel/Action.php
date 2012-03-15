<?php
class KernelActionsAction extends KernelObject{
	
	public function __construct($config=null){
		parent::__construct($config);
		
		$this->setObjectName();
		
		$this->addField('Status', 'Kernel.Object.String', true, false);
	}
	
	public function run(){
		if(!$this->validate()){
			return false;
		}
		
		return true;
	}
}
?>