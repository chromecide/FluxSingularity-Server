<?php
class KernelTasksMessagesRemoveMessageFromQueue extends KernelTasksTask{
	public function __construct(){
		parent::__construct(false);
		
		$this->_ClassName = 'Kernel.Tasks.Messages.RemoveMessageFromQueue';
		$this->_ClassTitle='Remove Queue Message';
		$this->_ClassDescription = 'Remove a Message from a Message Queue';
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
	