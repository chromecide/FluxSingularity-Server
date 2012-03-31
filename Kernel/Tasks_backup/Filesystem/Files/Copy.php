<?php
class KernelTasksFilesystemFilesCopy extends KernelTasksTask{
	public function __construct($data){
		parent::__construct(false);
		
		$this->_ClassName = 'Kernel.Tasks.Filesystem.Files.Copy';
		$this->_ClassTitle='Copy a File';
		$this->_ClassDescription = 'Copies a file from one folder to another';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com>';
		$this->_ClassVersion = '0.0.1';
		
		$this->inputs['File'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'File Path', 'Type'=>'Kernel.Data.Filesystem.File', 'Required'=>true, 'AllowList'=>true));
		$this->inputs['DestinationFolder'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Destination Folder', 'Type'=>'Kernel.Data.Filesystem.Folder', 'Required'=>true, 'AllowList'=>true));
		
		$this->outputs['FileCopied'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'File Copied', 'Type'=>'Kernel.Data.Primitive.Boolean'));
		$this->outputs['FileNotCopied'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'File Not Copied', 'Type'=>'Kernel.Data.Primitive.Boolean'));
		
	}
	
	public function run(){
		if(!parent::run()){
			return false;
		}
		
		//validate the file path
		
		
		//validate the destination path
		
		
		//copy the file
		
		
		return $this->completeTask();
	}
} 
?>