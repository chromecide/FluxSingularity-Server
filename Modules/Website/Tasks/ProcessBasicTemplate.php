<?php
//this is a glorified wrapper for Kernel.Tasks.Data.String.ProcessKeyValueString
// simply because i think people will expect a task like this to exist within the website module
//even though it does exactly the same thing as it's parent class. Cheers. 
class ModulesWebsiteTasksProcessBasicTemplate extends KernelTasksDataStringProcessKeyValueString{
	public function __construct(){
		parent::__construct();
		
		$this->_ClassName = 'Modules.Website.Tasks.ProcessBasicTemplate';
		$this->_ClassTitle='Website - Process Basic Template';
		$this->_ClassDescription = 'Processes a HTML String with basic templates using key/value pairs.  Templates are in the format of "{item Name}"';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '1.0.0';
	}
}
?>