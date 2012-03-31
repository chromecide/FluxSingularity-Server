<?php
class KernelActionsStop extends KernelObject{
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->useDefinition('Object.Action');
		
		$this->setValue('ID','Kernel.Action.Stop');
		$this->setValue('Name','Stop');
		$this->setValue('Description', 'Prevents any more actions from being fired for the Event');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
	}
	
	public function run(){
		return false;
	}
}
?>