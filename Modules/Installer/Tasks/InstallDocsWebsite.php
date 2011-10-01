<?php
class ModulesInstallerTasksInstallDocsWebsite extends KernelTasksTask{
	public function __construct(){
		parent::__construct();
		
		$this->_ClassName = 'Modules.Installer.Tasks.InstallDocsWebsite';
		$this->_ClassTitle='Install Documentation Sub Web';
		$this->_ClassDescription = 'Installs the documentation into a data store using the supplied data';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.8.0';
		
		//Inputs
		$this->inputs['Domain'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Domain', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		$this->inputs['PagePath'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Page Path', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>false, 'AllowList'=>false));
		
		//Outputs
		$this->outputs['WebsiteInstalled'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Website Installed', 'Type'=>'Kernel.Data.Primitive.Boolean'));
		$this->outputs['WebsiteNotInstalled'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Website Installed', 'Type'=>'Kernel.Data.Primitive.Boolean'));
	}

	public function runTask(){
		if(!parent::runTask()){
			return false;
		}
		
		//Load Inputs
		$domain = $this->getTaskInput('Domain');
		$pagePath = $this->getTaskInput('PagePath');

		$succeeded = true;
		
		$header = DataClassLoader::createInstance('Modules.Website.Data.SimpleHeader');
		$headerHtml = '<table><tr><td>Navigation</td><td>Main Content</td>';
		$header->setValue('HTML', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $headerHtml));
		$header->save();
		
		$footer = DataClassLoader::createInstance('Modules.Website.Data.SimpleFooter');
		$footerHtml = '<table><tr><td>Navigation</td><td>Main Content</td>';
		$footer->setValue('HTML', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $footerHtml));
		$footer->save();
		
		$site = DataClassLoader::createInstance('Modules.Website.Data.SimplePage');
		
		
		if($succeeded){
			$this->setTaskOutput('WebsiteInstalled', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
			$this->setTaskOutput('WebsiteNotInstalled', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));
		}else{
			$this->setTaskOutput('WebsiteInstalled', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));
			$this->setTaskOutput('WebsiteNotInstalled', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
		}
		
		return $this->completeTask();
	}
	
}
?>