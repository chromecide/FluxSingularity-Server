<?php
class KernelActionsRunProcess extends KernelActionsAction{
	private $_taskList = array();
	private $_objectMap = array();
	
	public function __construct($cfg=null){
		parent::__construct($cfg);
	
		$this->addField('LocalData', 'Kernel.Object', false, true);
		$this->addField('Tasks', 'Kernel.Task', true, true);
		$this->addField('ObjectMap', 'Kernel.Object.String', true, false);	
	}
	
	public function run(){
		
	}
	
	
}
?>