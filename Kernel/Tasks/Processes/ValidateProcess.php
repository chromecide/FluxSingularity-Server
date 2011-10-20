<?php
class KernelTasksProcessesValidateProcess extends KernelTasksTask{
	public function __construct(){
		parent::__construct(false);
		
		$this->_ClassName = 'Kernel.Tasks.Processes.ValidateProcess';
		$this->_ClassTitle='Validate Process';
		$this->_ClassDescription = 'Validates a Process';
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
	