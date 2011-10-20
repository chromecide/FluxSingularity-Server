<?php
class KernelTasksSecurityRemoveUserFromCircle extends KernelTasksTask{
	public function __construct(){
		parent::__construct(false);
		
		$this->_ClassName = 'Kernel.Tasks.Security.RemoveUserFromCircle';
		$this->_ClassTitle='Remove User From Circle';
		$this->_ClassDescription = 'Rmoves a User from a Circle';
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
	