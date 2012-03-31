<?php
class KernelEventsFilesystemFolderCreated extends KernelEventsEvent{
	public function __construct(){
		parent::__construct();
		
		$this->_ClassName = 'Kernel.Events.Filesystem.FolderCreated';
		$this->_ClassTitle='Folder Created Event';
		$this->_ClassDescription = 'This event is fired whenever a Folder is successfully created within the system.  This is deisgned primarily for debugging purposes, use with caution';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.2.0';
		
		$this->outputs['Folder'] = array('Folder', 'Kernel.Data.Filesystem.Folder', true, true);
	}
	
	public function fire(){
		parent::fire();
	}
}
?>