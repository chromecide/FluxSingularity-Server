<?php
class KernelTasksFilesystemFilesMove extends KernelTasksTask{
	public function __construct($data){
		parent::__construct(false);
		
		$this->_ClassName = 'Kernel.Tasks.Filesystem.Files.Moves';
		$this->_ClassTitle='Copy a File';
		$this->_ClassDescription = 'Moves a file from one folder to another';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com>';
		$this->_ClassVersion = '0.0.1';
		
		$this->inputs['Filepath'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'File Path', 'Type'=>'Kernel.Data.Filesystem.File', 'Required'=>true, 'AllowList'=>true));
		$this->inputs['DestinationFolder'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Destination Folder', 'Type'=>'Kernel.Data.Filesystem.Folder', 'Required'=>true, 'AllowList'=>true));
		
		$this->outputs['FileMoved'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'File Moved', 'Type'=>'Kernel.Data.Primitive.Boolean'));
		$this->outputs['FileNotMoved'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'File Not Moved', 'Type'=>'Kernel.Data.Primitive.Boolean'));
		
	}
	
	public function run(){
		if(!parent::run()){
			return false;
		}
		
		//create an instance of the copy task and execute that
		
		//create an instance of the delete task and execute that
		
		return $this->completeTask();
	}
} 
?>