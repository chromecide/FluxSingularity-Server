<?php
class KernelEventsMessagesMessageRemovedFromQueue extends KernelEventsEvent{
	public function __construct(){
		parent::__construct();
		
		$this->_ClassName = 'Kernel.Events.Messages.MessageRecieved';
		$this->_ClassTitle='Message Removed From Queue Event';
		$this->_ClassDescription = 'This event is fired whenever a message is recieved';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.5.0';
		
		$this->outputs['Message'] = array('Message', 'Kernel.Data.Messages.Message', true, true);
		$this->outputs['MessageQueue'] = array('MessageQueue', 'Kernel.Data.Messages.MessageQueue', true, true);
	}
	
	public function fire(){
		parent::fire();
	}
}
?>