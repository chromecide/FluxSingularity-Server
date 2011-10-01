<?php
class KernelEventsProcessesProcessExecuted extends KernelEventsEvent{
	public function __construct(){
		parent::__construct();
		
		$this->_ClassName = 'Kernel.Events.Process.ProcessExecuted';
		$this->_ClassTitle='Process Executed Event';
		$this->_ClassDescription = 'This event is fired whenever a Process is successfully executed within the system.  This is deisgned primarily for debugging purposes, use with caution';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.5.0';
		
		$this->outputs['Process'] = array('Process', 'Kernel.Data.Primitive.String', true, true);
	}
	
	public function fire(){
		parent::fire();
	}
}
?>