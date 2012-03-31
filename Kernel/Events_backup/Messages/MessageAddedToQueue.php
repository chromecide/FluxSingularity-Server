<?php
class KernelEventsMessagesMessageAddedToQueue extends KernelEventsEvent{
	public function __construct(){
		parent::__construct();
		
		$this->_ClassName = 'Kernel.Events.Messages.MessageAddedToQueue';
		$this->_ClassTitle='Message Added to Queue Event';
		$this->_ClassDescription = 'This event is fired whenever a message is added to a queue.';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.2.0';
		
		$this->outputs['Message'] = array('Message', 'Kernel.Data.Messages.Message', true, true);
		$this->outputs['MessageQueue'] = array('MessageQueue', 'Kernel.Data.Messages.MessageQueue', true, true);
	}
	
	public function fire(){
		parent::fire();
	}
}
?>