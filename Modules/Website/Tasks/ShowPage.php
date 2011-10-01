<?php 
class ModulesWebsiteTasksShowPage extends KernelTasksTask{
	public function __construct(){
		$this->inputs['Domain'] = array('Kernel.Data.Primitive.String', true, false);
		$this->inputs['PagePath'] = array('Kernel.Data.Primitive.String', false, false);
		$this->inputs['PostData'] = array('Kernel.Data.Primitive.String', false, true);
		$this->inputs['GetData'] = array('Kernel.Data.Primitive.String', false, true);
		
		$this->outputs['PageHTML'] = array('Kernel.Data.Primitive.String');
		$this->outputs['Errors'] = array('Kernel.Data.Primitive.String', true);
		parent::__construct();
	}
	
	public function runTask(){
		
		//determine if the page requested exists
		$pageObj = DataClassLoader::createInstance('Modules.Website.Data.StaticPage');
		
		$params = array();
		
		$params['Domain'] = $this->getTaskInput('Domain');

		$pages = $pageObj->find($params);
		
		if($pages->Count()>0){
			$page = $pages->getItem(0);
			$this->setTaskOutput('PageHTML', $page->get('PageHTML'));
		}else{
			$this->setTaskOutput('PageHTML', '<b>page not found</b>');
		}
		parent::runTask();
	}
}
?>