<?php 
class ModulesWebsiteTasksLoadStaticPage extends KernelTasksTask{
	public function __construct(){
		parent::__construct();
		
		$this->_ClassName = 'Modules.Website.Tasks.LoadStaticPage';
		$this->_ClassTitle='Load Static Web Site Page';
		$this->_ClassDescription = 'This task will load a static website page based on the supplied domain and page path';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.7.0';
		
		//Inputs
		$this->inputs['Store'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', $sourceDef);
		$this->inputs['Domain'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Domain', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		$this->inputs['PagePath'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'PagePath', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		
		//Outputs
		$this->outputs['PageLoaded'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'PageLoaded', 'Type'=>'Kernel.Data.Primitive.Boolean', 'Required'=>true, 'AllowList'=>false));
		$this->outputs['PageNotLoaded'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'PageNotLoaded', 'Type'=>'Kernel.Data.Primitive.Boolean', 'Required'=>true, 'AllowList'=>false));
		$this->outputs['WebsitePage'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'WebsitePage', 'Type'=>'Modules.Website.Data.StaticPage', 'Required'=>true, 'AllowList'=>false));
	}

	public function runTask(){
		$store = $this->getTaskInput('Store');
		
		if(!$store){
			$store = getKernelStore();
		}
		
		$domain = $this->getTaskInput('Domain');
		$pagePath = $this->getTaskInput('PagePath');
		
		$params = array(
			'Type'=>'AND',
			'Conditions'=>array(
				array(
					'Attribute'=>'Domain',
					'Operator'=>'=',
					'Value'=>$domain
				),
				array(
					'Attribute'=>'PagePath',
					'Operator'=>'=',
					'Value'=>$pagePath
				),
				array(
					'Attribute'=>'KernelClass',
					'Operator'=>'=',
					'Value'=>'Modules.Website.Data.StaticPage'
				)	
			)
		);
		
		$page = DataClassLoader::createInstance('Modules.Website.Data.StaticPage');
		
		$conditions = DataClassLoader::createInstance('Kernel.Data.Primitive.ConditionGroup', $params);
		
		$retPage = $store->findOne($page, $conditions);
		
		if(!$retPage){
			$retPage = $page;
			$html = DataClassLoader::createInstance('Kernel.Data.Primitive.String', '<h1>404: Page Not Found</h1>');
			$retPage->setValue('WebsitePage', $retPage);
			
			$this->setTaskOutput('PageLoaded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));
			$this->setTaskOutput('PageNotLoaded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
		}else{
			$this->setTaskOutput('PageLoaded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
			$this->setTaskOutput('PageNotLoaded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));
		}
		
		$this->setTaskOutput('WebsitePage', $retPage);
		
		$this->completeTask();
	}
}
?>