<?php
class KernelTasksProcessesLoadProcessById extends KernelTasksTask{
	public function __construct(){
		parent::__construct();
		
		$this->_ClassName = 'Kernel.Tasks.Processes.LoadProcessById';
		$this->_ClassTitle='Load Process by ID';
		$this->_ClassDescription = 'Load a Process using the supplied ID number';
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
	