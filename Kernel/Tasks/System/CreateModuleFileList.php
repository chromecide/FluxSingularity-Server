<?php
class KernelTasksSystemCreateModuleFileList extends KernelTasksTask{
	public function __construct(){
		parent::__construct();
		
		$this->_ClassName = 'Kernel.Tasks.System.CreateModuleFileList';
		$this->_ClassTitle='Create Module File List';
		$this->_ClassDescription = 'This Task will create a list of the Kernel Files in the supplied format';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.8.0';
		
		//Inputs
		$this->inputs['Format'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Format', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		$this->inputs['ModuleName'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Module Name', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>false, 'AllowList'=>false));
		$this->inputs['IncludeMeta'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'IncludeMeta', 'Type'=>'Kernel.Data.Primitive.Boolean', 'Required'=>false, 'AllowList'=>false));
		
		//Outputs
		$this->outputs['HTML'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'HTML', 'Type'=>'Kernel.Data.Primitive.String'));
		$this->outputs['NamedList'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'NamedList', 'Type'=>'Kernel.Data.Primitive.String'));
	}

	public function runTask(){
		if(!parent::runTask()){
			return false;
		}
		
		//Load Inputs
		$format = $this->getTaskInput('Format');
		$moduleName = $this->getTaskInput('ModuleName');
		$includeMeta = $this->getTaskInput('IncludeMeta');
		
		//load the file list into an array
		$fileArray = $this->kernelFilesToArray();
		
		$modulePath = 'Modules';
		
		if($moduleName){
			$modulePath .='.'.$moduleName->getValue();
		}

		switch($format->getValue()){
			case 'HTML':
				$return = $this->createHtml($fileArray, 0, $modulePath, $includeMeta->getValue());
			    $this->setTaskOutput('HTML', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $return));
				break;
			case 'NamedList':
				$return = $this->createList($fileArray);
				$this->setTaskOutput('NamedList', $return);
				break;
		}
		
		return $this->completeTask();
	}
	
	private function kernelFilesToArray(){
		$list = $this->createFileList(realpath(dirname(__FILE__).'/../../../Modules/Website'));
		return $list;
	}

	private function createFileList($path){
		//$path = realpath($path);
		
		$kh = opendir($path);
		$dirs = array();
		$files = array();
		
		if($kh){
			while (false !== ($file = readdir($kh))) {
				if(is_dir($path.'/'.$file)){
					if($file!='..' && $file!='.'){
						$dirs[$file] = $this->createFileList($path.'/'.$file);
					}
				}else{
					$files[$file] = $file;
				}
		    }	
		}

		$dir = array_merge($files, $dirs);
		
		closedir($kh);
		return $dir;
	}
	
	private function createHTML($fileArray, $currentDepth=0, $currentKernelPath='Kernel', $includeMeta=false){
		
		//print_r($fileArray);
		$string = '';
		if($currentDepth==0){
			$string .= '<table border="0" style="border:1px solid black" width="100%"><tr><th>File Name</th>';
			if($includeMeta){
				$string .='<th>Class Name</th>';
				$string .= '<th>Class Title</th>';
				$string .= '<th>Class Version</th>';
				$string .= '<th>Class Author</th>';
				$string .= '<th>Class Description</th>';
				
				$string .= '</tr>';	
			}
		}
		foreach($fileArray as $file=>$subs){
			$meta = $meta = new stdClass();
			$meta->Name = '&nbsp;';
			$meta->Title = '&nbsp;';
			$meta->Description = '&nbsp;';
			$meta->Author = '&nbsp;';
			$meta->Version = '&nbsp;';
			
			
			if(is_array($subs)){
				//its a directory
				$currentPathName = $currentKernelPath.'.'.$file;
			}else{
				if(strpos($file, '.php')){
					$currentPathName = $currentKernelPath.'.'.str_replace('.php', '', $file);
					
					switch($currentPathName){
						case 'Kernel.DataClassLoader':
							$meta = DataClassLoader::getMeta(); 
							break;
						case 'Kernel.DataNormalisation.util':
							$meta = DataNormalization::getMeta();
							break;
						case 'Kernel.DataValidation.util':
							$meta = DataValidation::getMeta();
							break;
						case 'Kernel.Data.Data':
							$instance = DataClassLoader::createInstance('Kernel.Data');
							$meta = $instance->getClassMeta();
							break;
						case 'Kernel.Kernel':
							$item = new Kernel();
							$meta = $item->getMeta();
							break;
						case 'Kernel.Data.Database':
							
							//$meta = KernelDataDatabase::getMeta();
							break;
						default:
							
							try{
								$instance = DataClassLoader::createInstance($currentPathName);
							}catch (Exception $e){
								//print_r($e);
								$instance = false;
							}
							
							if($instance){
								//$className = $instance->getClassName();
								$meta = $instance->getClassMeta();
							}else{
								$meta->Name = 'Could Not Create Instance';
							}
							break;
					}
					
				}else{
					$currentPathName = $currentKernelPath.'.'.$file;	
				}
			}
			
			
			$string .= '<tr><td>';
			
			for($i=0;$i<$currentDepth;$i++){
				$string .= '&nbsp;&nbsp;&nbsp;&nbsp;';
			}
			
			$string .= '- ';
			$string .= $file;
			$string .= '</td>';
			if($includeMeta){
				$string .= '<td valign="top">'.$meta->Name.'</td>';
				$string .= '<td valign="top">'.$meta->Title.'</td>';
				$string .= '<td valign="top">'.$meta->Version.'</td>';
				$string .= '<td valign="top">'.$meta->Author.'</td>';
				$string .= '<td valign="top">'.$meta->Description.'</td>';
				$string .= '</tr>';	
			}
			
			if(is_array($subs)){
				$string .= $this->createHTML($subs, $currentDepth+1, $currentPathName, $includeMeta);
			}
		}
		if($currentDepth==0){
			$string .= '</table>';
		}
		return $string;
	}
	
	private function createList($fileArray, $parentPath='/'){
		$list = DataClassLoader::createInstance('Kernel.Data.Primitive.NamedList');
		
		foreach($fileArray as $file=>$subs){
			
			if(is_array($subs)){
				$item = $this->createList($subs);
			}else{
				$item = DataClassLoader::createInstance('Kernel.Data.Primitive.String', $file);
			}
			$list->addItem($file, $item);
		}
		return $list;
	}
}
?>