<?php

class KernelEventsSecurityUserLoggedOut extends KernelEventsEvent{
	public function __construct(){
		parent::__construct();
		
		$this->_ClassName = 'Kernel.Events.Security.UserLoggedOut';
		$this->_ClassTitle='User Logged Out Event';
		$this->_ClassDescription = 'This event is fired whenever a User Logs out of the system';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.2.0';
		
		$this->outputs['Client'] = array('Client', 'Kernel.Data.Primitive.String', true, false);
		$this->outputs['User'] = array('User', 'Kernel.Data.Security.User', true, false);
	}
	
	public function fire(){
		parent::fire();
	}
}
