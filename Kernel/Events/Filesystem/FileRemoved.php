<?php
class KernelEventsFilesystemFileRemoved extends KernelEventsEvent{
	public function __construct(){
		parent::__construct();
		
		$this->_ClassName = 'Kernel.Events.Filesystem.FileRemoved';
		$this->_ClassTitle='File Removed Event';
		$this->_ClassDescription = 'This event is fired whenever a file is successfully Removed from the system.  This is deisgned primarily for debugging purposes, use with caution';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.2.0';
		
		$this->outputs['File'] = array('File', 'Kernel.Data.Filesystem.File', true, true);
	}
	
	public function fire(){
		parent::fire();
	}
}
?>