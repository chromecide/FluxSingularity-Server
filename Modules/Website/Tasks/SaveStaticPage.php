<?php 
class ModulesWebsiteTasksSaveStaticPage extends KernelTasksTask{
	public function __construct(){
		$this->inputs['Domain'] = array('Kernel.Data.Primitive.String', true, false);
		$this->inputs['Title'] = array('Kernel.Data.Primitive.String', true, false);
		$this->inputs['PageHTML'] = array('Kernel.Data.Primitive.String', false, false);
		
		$this->outputs['SaveSuccessful'] = array('Kernel.Data.Primitive.Boolean');
		$this->outputs['SaveErrors'] = array('Kernel.Data.Primitive.String', true);
		
		parent::__construct();
	}
	
	public function runTask(){
		
		//determine if the page requested exists
		$pageObj = DataClassLoader::createInstance('Modules.Website.Data.StaticPage');
		$pageObj->set('KernelTitle', $this->getTaskInput('Title'));
		$pageObj->set('Domain', $this->getTaskInput('Domain'));
		$pageObj->set('PageHTML', $this->getTaskInput('PageHTML'));
		
		if($pageObj->save()){
			$this->setTaskOutput('SaveSuccessful', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
		}else{
			print_r($pageObj->validationErrors);
			
		}
		parent::runTask();
	}
}
?>