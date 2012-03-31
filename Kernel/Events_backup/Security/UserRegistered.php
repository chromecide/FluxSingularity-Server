<?php

class KernelEventsSecurityUserRegistered extends KernelEventsEvent{
	public function __construct(){
		parent::__construct();
		
		$this->_ClassName = 'Kernel.Events.Security.UserRegistered';
		$this->_ClassTitle='User Added to Circle Event';
		$this->_ClassDescription = 'This event is fired whenever a new user is registered';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.2.0';
		
		$this->outputs['RegistrationMethod'] = array('RegistrationMethod', 'Kernel.Data.Primitive.String', true, false);
		$this->outputs['User'] = array('User', 'Kernel.Data.Security.User', true, false);
	}
	
	public function fire(){
		parent::fire();
	}
}
