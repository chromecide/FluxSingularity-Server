<?php
class KernelTasksFilesystemGetExtension extends KernelTasksTask{
	public function __construct($data){
		parent::__construct(false);
		
		$this->_ClassName = 'Kernel.Tasks.Filesystem.Files.GetExtension';
		$this->_ClassTitle='Get File Extension';
		$this->_ClassDescription = 'Get the file extension from the filename';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com>';
		$this->_ClassVersion = '0.0.1';
		
		$this->inputs['File'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'File Path', 'Type'=>'Kernel.Data.Filesystem.File', 'Required'=>true, 'AllowList'=>true));
		
		$this->outputs['Extension'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'File Moved', 'Type'=>'Kernel.Data.Primitive.Boolean'));
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