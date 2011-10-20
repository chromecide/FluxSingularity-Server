<?php
class KernelTasksMessagesSetMessageStatus extends KernelTasksTask{
	public function __construct(){
		parent::__construct(false);
		
		$this->_ClassName = 'Kernel.Tasks.Messages.SetMessageStatus';
		$this->_ClassTitle='Set Message Status';
		$this->_ClassDescription = 'Sets the Status of a Message';
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
	