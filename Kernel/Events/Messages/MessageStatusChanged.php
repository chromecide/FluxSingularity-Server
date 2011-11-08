<?php
class KernelEventsMessagesMessageStatusChanged extends KernelEventsEvent{
	public function __construct(){
		parent::__construct();
		
		$this->_ClassName = 'Kernel.Events.Messages.MessageStatusChanged';
		$this->_ClassTitle='Message Status Changed Event';
		$this->_ClassDescription = 'This event is fired whenever a messages status changes';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.2.0';
		
		$this->outputs['Message'] = array('Message', 'Kernel.Data.Messages.Message', true, true);
	}
	
	public function fire(){
		parent::fire();
	}
}
?>