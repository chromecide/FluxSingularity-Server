<?php

class KernelEventsSecurityUserEmailAuthenticated extends KernelEventsEvent{
	public function __construct(){
		parent::__construct();
		
		$this->_ClassName = 'Kernel.Events.Security.UserEmailAuthenticated';
		$this->_ClassTitle='User Email Authenticated Event';
		$this->_ClassDescription = 'This event is fired whenever a user Email Address is successfully authenticated';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.2.0';
		
		$this->outputs['User'] = array('User', 'Kernel.Data.Security.User', true, false);
	}
	
	public function fire(){
		parent::fire();
	}
}
