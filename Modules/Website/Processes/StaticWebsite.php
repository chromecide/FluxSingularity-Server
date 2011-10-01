<?php 
class ModulesWebsiteProcessesStaticWebsite extends KernelProcessesProcess{
	public function __construct(){
		parent::__construct();
		
		$this->_ClassName = 'Modules.Website.Processes.StaticWebsite';
		$this->_ClassTitle='Static Website Process';
		$this->_ClassDescription = 'This process handles requests for static website pages';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '1.0.0';
		
		//Inputs
		$this->inputs['Domain'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Domain', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		$this->inputs['PagePath'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'PagePath', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		
		//outputs
		$this->outputs['HTML'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'HTML', 'Type'=>'Kernel.Data.Primitive.String'));
		
		$this->buildTaskMap();
	}
	
	public function buildTaskMap(){
		//build the show page task
		$loadWebPageTask = DataClassLoader::createInstance('Modules.Website.Tasks.LoadStaticPage');
		$this->tasks['LoadWebsitePage'] = $loadWebPageTask;
		
		$this->taskMap['Inputs'][] = array('Enabled'=>'LoadWebsitePage.Enabled');
		$this->taskMap['Inputs'][] = array('Domain'=>'LoadWebsitePage.Domain');
		$this->taskMap['Inputs'][] = array('PagePath'=>'LoadWebsitePage.PagePath');
		
		//$showPageTask = DataClassLoader::createInstance('Modules.Website.Tasks.ShowPage');
		//$this->tasks['ShowWebPage'] = $showPageTask;
		
		
		//build the output HTML Task
		$outputHTMLTask = DataClassLoader::createInstance('Modules.Website.Tasks.SendHTMLResponse');
		$this->tasks['OutputHTML'] = $outputHTMLTask;
		
		$this->taskMap['LoadWebsitePage'][] = array('PageLoaded'=>'OutputHTML.Enabled');
		$this->taskMap['LoadWebsitePage'][] = array('WebsitePage.PageHTML'=>'OutputHTML.HTMLString');
		
	}
}
?>