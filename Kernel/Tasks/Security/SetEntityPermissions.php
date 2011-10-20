<?php
class KernelTasksSecuritySetEntityPermissions extends KernelTasksTask{
	public function __construct(){
		parent::__construct(false);
		
		$this->_ClassName = 'Kernel.Tasks.Security.SetEntityPermissions';
		$this->_ClassTitle='Set Entity Permissions';
		$this->_ClassDescription = 'Sets the Security Permissions for a Data Entity';
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
	