<?php

class KernelEventsSecurityPermissionRecieved extends KernelEventsEvent{
	public function __construct(){
		parent::__construct();
		
		$this->_ClassName = 'Kernel.Events.Security.PermissionRecieved';
		$this->_ClassTitle='Permission Recieved Event';
		$this->_ClassDescription = 'This event is fired whenever Users or Circles are given permission to an entity or collection of entities';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.2.0';
		
		$this->outputs['Circles'] = array('Circles', 'Kernel.Data.Security.Circle', false, true);
		$this->outputs['Users'] = array('Users', 'Kernel.Data.Security.User', false, true);
		$this->outputs['Entities'] = array('Entities', 'Kernel.Data.Entity', true, true);
	}
	
	public function fire(){
		parent::fire();
	}
}
