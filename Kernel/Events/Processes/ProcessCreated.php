<?php
class KernelEventsProcessesProcessCreated extends KernelEventsEvent{
	public function __construct(){
		parent::__construct();
		
		$this->_ClassName = 'Kernel.Events.Process.ProcessCreated';
		$this->_ClassTitle='Process Created Event';
		$this->_ClassDescription = 'This event is fired whenever a Process is successfully created within the system.  This is deisgned primarily for debugging purposes, use with caution';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.5.0';
		
		$this->outputs['Process'] = array('Process', 'Kernel.Data.Primitive.String', true, true);
	}
	
	public function fire(){
		parent::fire();
	}
}
?>