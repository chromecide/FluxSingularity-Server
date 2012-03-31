<?php
class KernelTasksFilesystemFilesDelete extends KernelTasksTask{
	public function __construct($data){
		parent::__construct(false);
		
		$this->_ClassName = 'Kernel.Tasks.Filesystem.Files.Delete';
		$this->_ClassTitle='Delete a File';
		$this->_ClassDescription = 'Delete a file from a folder';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com>';
		$this->_ClassVersion = '0.0.1';
		
		$this->inputs['File'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'File Path', 'Type'=>'Kernel.Data.Filesystem.File', 'Required'=>true, 'AllowList'=>true));
		
		
		$this->outputs['FileDelete'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'File Moved', 'Type'=>'Kernel.Data.Primitive.Boolean'));
		$this->outputs['FileDeleted'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'File Not Moved', 'Type'=>'Kernel.Data.Primitive.Boolean'));
		
	}
	
	public function run(){
		if(!parent::run()){
			return false;
		}
		
		//validate the file path
		
		//delete the file
		
		return $this->completeTask();
	}
} 
?>