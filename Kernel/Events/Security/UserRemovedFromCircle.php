<?php

class KernelEventsSecurityUserRemovedFromCircle extends KernelEventsEvent{
	public function __construct(){
		parent::__construct();
		
		$this->_ClassName = 'Kernel.Events.Security.UserRemovedFromCircle';
		$this->_ClassTitle='User Removed From Circle Event';
		$this->_ClassDescription = 'This event is fired whenever a User is removed from a Circle';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.5.0';
		
		$this->outputs['Circle'] = array('Circle', 'Kernel.Data.Security.Circle', true, false);
		$this->outputs['User'] = array('User', 'Kernel.Data.Security.User', true, false);
	}
	
	public function fire(){
		parent::fire();
	}
}
