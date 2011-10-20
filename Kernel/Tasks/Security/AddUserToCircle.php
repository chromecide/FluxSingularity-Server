<?php
class KernelTasksSecurityAddUserToCircle extends KernelTasksTask{
	public function __construct(){
		parent::__construct(false);
		
		$this->_ClassName = 'Kernel.Tasks.Security.AddUserToCircle';
		$this->_ClassTitle='Add User to Circle';
		$this->_ClassDescription = 'Adds a User to a Security Circle';
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
	