<?php
class KernelTasksFilesystemFoldersGetContents extends KernelTasksTask{
	public function __construct($data){
		parent::__construct(false);
		
		$this->_ClassName = 'Kernel.Tasks.Filesystem.Folders.GetContents';
		$this->_ClassTitle='Get Folder Contents';
		$this->_ClassDescription = 'Gets the contents of a folder as Flux Singularity Data Objects';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com>';
		$this->_ClassVersion = '0.0.1';
		
		$this->inputs['Folder'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'File Path', 'Type'=>'Kernel.Data.Filesystem.Folder', 'Required'=>true, 'AllowList'=>false));
		
		$this->outputs['ContentLoaded'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'File Moved', 'Type'=>'Kernel.Data.Primitive.Boolean'));
		$this->outputs['ContentNotLoaded'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'File Not Moved', 'Type'=>'Kernel.Data.Primitive.Boolean'));
		$this->outputs['InvalidPath'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Invalid Path', 'Type'=>'Kernel.Data.Primitive.Boolean'));
	}
	
	public function run(){
		if(!parent::run()){
			return false;
		}
		
		$pathInvalid = false;
		$loaded = false;
		
		$folder = $this->getInputValue('Folder');
		
		$path = $folder->getValue('Path');
		$pathString = $path->getValue();
		
		if(!file_exists($pathString)){
			$this->addError($this->getClassName(), 'Folder Path does not Exist');
			$loaded = false;
			$pathInvalid = true;
		}else{			
			if ($handle = opendir($pathString)) {
			   // echo "Directory handle: $handle\n";
			   // echo "Files:<br/>\n";
			
			    /* This is the correct way to loop over the directory. */
			    while (false !== ($file = readdir($handle))) {
			    	if(!is_dir($file)){
			    		$oFile = DataClassLoader::createInstance('Kernel.Data.Filesystem.File');
						
						$oFile->setValue('Name', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $file));
						$oFile->setValue('Path', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $pathString.'/'.$file));
			    		$folder->addFile($oFile);
			    	}
			        
			    }
				
			    closedir($handle);
			}
		}
		
		$this->setOutputValue('ContentLoaded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', $loaded));
		$this->setOutputValue('ContentNotLoaded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', !$loaded));
		
		$this->setOutputValue('InvalidPath', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', $pathInvalid));
		
		$this->setOutputValue('Folder', $folder);
		print_r($folder);
		return $this->completeTask();
	}
} 
?>