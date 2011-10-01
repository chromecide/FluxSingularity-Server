<?php
class KernelTasksSystemCreateKernelFileList extends KernelTasksTask{
	public function __construct(){
		parent::__construct();
		
		$this->_ClassName = 'Kernel.Tasks.System.CreateKernelFileList';
		$this->_ClassTitle='Create Kernel File List';
		$this->_ClassDescription = 'This Task will create a list of the Kernel Files in the supplied format';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.6.0';
		
		//Inputs
		$this->inputs['Format'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Format', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		$this->inputs['IncludeMeta'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'IncludeMeta', 'Type'=>'Kernel.Data.Primitive.Boolean', 'Required'=>false, 'AllowList'=>false));
		
		$this->setTaskInput('IncludeMeta', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));
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
		$includeMeta = $this->getTaskInput('IncludeMeta');
		
		//load the file list into an array
		$fileArray = $this->kernelFilesToArray();
		
		switch($format->getValue()){
			case 'HTML':
				$return = $this->createHtml($fileArray, 0, 'Kernel', $includeMeta->getValue());
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
		$list = $this->createFileList(realpath(dirname(__FILE__).'/../../../Kernel'));
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
		$string .= '<style>';
		$string .= 'ul.fileList{list-style-type:none; margin:0;padding:0;font-family:arial, verdana;}';
		$string .= 'ul.fileList ul{list-style-type:none; margin:0;padding:0;}';
		$string .= 'ul.fileList li{list-style-type:none; margin-left:0;}';
		$string .= '.item{width:98%;float:left;clear:both; margin-bottom: 5px;margin-left: 10px;}';
		$string .= '.file_name{font-weight: bold;width:100%; clear:both; border-bottom: 1px solid #ccc;font-size:1.5em;color: #8F8F8F;}';
		$string .= '.meta{float:left;padding: 2 2 2 2;}';
		$string .= '.title{width: 80%; padding-left:10px;}';
		$string .= '.version{float: right;}';
		$string .= '.classname{width: 80%; font-size: 0.9em;padding-left:10px; color: #ccc;}';
		$string .= '.author{float:right; font-size: 0.9em;text-align:right; color: #ccc;}';
		$string .= '.description{float:left; clear:both; padding-left:10px; margin-bottom:20px; width:100%;}';
		$string .= '</style>';
		if($currentDepth==0){
			$string .='<ul class="filelist"><li>';
			$string .= '<h1>Kernel</h1>';
			$string .= '<ul>';
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
							$meta = KernelDataDatabase::getMeta();
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
			
			if(is_array($subs)){
				$string .= '<li><h'.($currentDepth+2).'>'.str_replace('.', '/', $currentPathName).'</h'.($currentDepth+2).'><ul>';
				$string .= $this->createHTML($subs, $currentDepth+1, $currentPathName, $includeMeta);
				$string .= '</ul>';
			}else{
				$string .= '<li>';
				$string .= '<div class="item">';
				$string .= '<div class="file_name">';
				if($includeMeta){
					$string .= $meta->Title.'('.$file.')';
					$string .= '<div class="meta version">v'.$meta->Version.'</div>';
				}else{
					$string .= $file;	
				}
				
				$string.='</div>';
				if($includeMeta){
					$string .= '<div class="meta classname">'.$currentPathName.'</div>';
					$string .= '<div class="meta author">'.htmlspecialchars($meta->Author).'</div>';
					$string .= '<div class="meta description">'.$meta->Description.'</div>';
				}
				$string .= '</div>';
				$string .= '</li>';
			}
			
			/*
			for($i=0;$i<$currentDepth;$i++){
				$string .= '&nbsp;&nbsp;&nbsp;&nbsp;';
			}
			
			if(is_array($subs) && count($subs)>0){
				
			}else{
				
				$string .= '- ';
				$string .= $file;	
			}
			
			$string .= '</td>';
			if($includeMeta){
				if(count($subs)>1){
					
				}else{
					$string .= '<td valign="top">'.$meta->Name.'</td>';
					$string .= '<td valign="top">'.$meta->Title.'</td>';
					$string .= '<td valign="top">'.$meta->Version.'</td>';
					$string .= '<td valign="top">'.$meta->Author.'</td>';
					$string .= '<td valign="top">'.$meta->Description.'</td>';
					$string .= '</tr>';	
				}
					
			}
			
			if(is_array($subs)){
				$string .= $this->createHTML($subs, $currentDepth+1, $currentPathName, $includeMeta);
			}*/
		}
		if($currentDepth==0){
			$string .= '</ul>';
		}
		return $string;
	}
	
	private function createList($fileArray, $parentPath='/'){
		
		$list = DataClassLoader::createInstance('Kernel.Data.Primitive.NamedList');
		
		foreach($fileArray as $file=>$subs){	
			if(is_array($subs)){
				$item = $this->createList($subs);
			}else{
				$item = DataClassLoader::createInstance('Kernel.Data.Primitive.String', $subs);
			}
			$list->addItem($file, $item);
		}
		return $list;
	}
}
?>