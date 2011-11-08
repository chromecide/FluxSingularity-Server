<?php
class KernelTasksMessagesGetMessageFromQueue extends KernelTasksTask{
	public function __construct(){
		parent::__construct(false);
		
		$this->_ClassName = 'Kernel.Tasks.Messages.GetMessageFromQueue';
		$this->_ClassTitle='Add Message To Queue';
		$this->_ClassDescription = 'Add a Message to a Message Queue';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.0.1';
	}
	
	public function runTask(){
		if(!parent::runTask()){
			return false;
		}
		
		$this->completeTask();
	}
}
	