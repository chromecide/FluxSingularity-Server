<?php
class KernelEventsTasksTaskCreated extends KernelEventsEvent{
	public function __construct(){
		parent::__construct();
		
		$this->_ClassName = 'Kernel.Events.Task.TaskCreated';
		$this->_ClassTitle='Task Created Event';
		$this->_ClassDescription = 'This event is fired whenever a Task is successfully created within the system.  This is deisgned primarily for debugging purposes, use with caution';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.5.0';
		
		$this->outputs['Task'] = array('Task', 'Kernel.Data.Primitive.String', true, true);
	}
	
	public function fire(){
		parent::fire();
	}
}
?>