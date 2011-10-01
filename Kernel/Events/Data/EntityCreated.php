<?php
class KernelEventsDataEntityCreated extends KernelEventsEvent{
	public function __construct(){
		parent::__construct();
		
		$this->_ClassName = 'Kernel.Events.Data.EntityCreated';
		$this->_ClassTitle='Entity Created Event';
		$this->_ClassDescription = 'This event is fired whenever a new entity is successfully saved to the database.  This is deisgned primarily for debugging purposes, use with caution';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.5.0';
		
		$this->outputs['DataStore'] = array('DataStore', 'Kernel.Data.DataStore', true, true);
		$this->outputs['Entity'] = array('Entity', 'Kernel.Data.Entity', true, true);
	}
	
	public function fire(){
		parent::fire();
	}
}
?>