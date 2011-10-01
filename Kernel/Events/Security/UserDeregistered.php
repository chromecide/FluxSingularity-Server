<?php

class KernelEventsSecurityUserDeregistered extends KernelEventsEvent{
	public function __construct(){
		parent::__construct();
		
		$this->_ClassName = 'Kernel.Events.Security.UserDeregistered';
		$this->_ClassTitle='User Deregistered Event';
		$this->_ClassDescription = 'This event is fired whenever a user is Deregistered from the system';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.2.0';
		
		$this->outputs['User'] = array('User', 'Kernel.Data.Security.User', true, false);
		$this->outputs['Reason'] = array('Reason', 'Kernel.Data.Primitive.String', true, false);
	}
	
	public function fire(){
		parent::fire();
	}
}
