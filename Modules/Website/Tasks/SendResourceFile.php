<?php 
class ModulesWebsiteTasksSendResourceFile extends KernelTasksTask{
	
	public function __construct(){
		$this->kernelClass='Modules.Website.Tasks.SendResourceFile';
		$this->title='Website - Send Resource File';
		$this->description='Send a file to the client as a file';
		$this->author='Justin Pradier <justin.pradier@fluxsingularity.com>';
		
		$this->inputs['FileName'] = array('FileName', 'Kernel.Data.Primitive.String', true, false);
		$this->inputs['FileType'] = array('FileType', 'Kernel.Data.Primitivve.String', true, false);
		
		$this->outputs['Errors'] = array('Kernel.Data.Primitive.String', true);
		
		parent::__construct();
	}
	
	public function runTask(){
		$fileName = $this->getTaskInput('FileName');
		$fileType = $this->getTaskInput('FileType');
		//echo 'loading: '.$fileName.'<br/>';
		if(!$fileType){
			//detect file type from the filename
			$fileInfo = pathinfo($fileName);
			$extension = $fileInfo['extension'];
		}else{
			$extension = $fileType->get();
		}
		
		$contentTypeString = $this->getContentTypeFromExtension($extension);
		
		//ensure the file exists
		$contentTypeString = 'Content-type: '.$contentTypeString;
		$file = $fileName->get();
		
		//output the file
		header($contentTypeString);
		readfile($file);
		exit(0);
	}
	
	public function getContentTypeFromExtension($extension){
		switch($extension){
			case 'css':
				$ct = 'text/css';
				break;
		}
		return $ct;
	}
}
?>