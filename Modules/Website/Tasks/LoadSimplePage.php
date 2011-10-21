<?php 
class ModulesWebsiteTasksLoadSimplePage extends KernelTasksTask{
	public function __construct($config){
		parent::__construct($config);
		
		$this->_ClassName = 'Modules.Website.Tasks.LoadSimplePage';
		$this->_ClassTitle='Load Simple Web Site Page';
		$this->_ClassDescription = 'This task will load a Simple website page based on the supplied domain and page path';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.7.0';
		
		//Inputs
		$this->inputs['Store'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Store', 'Type'=>'Kernel.Data.DataStore', 'Required'=>true, 'AllowList'=>false));
		$this->inputs['Domain'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Domain', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		$this->inputs['PagePath'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'PagePath', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		
		//Outputs
		$this->outputs['PageLoaded'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'PageLoaded', 'Type'=>'Kernel.Data.Primitive.Boolean', 'AllowList'=>false));
		$this->outputs['PageNotLoaded'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'PageNotLoaded', 'Type'=>'Kernel.Data.Primitive.Boolean', 'Required'=>true, 'AllowList'=>false));
		$this->outputs['WebsitePage'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'WebsitePage', 'Type'=>'Modules.Website.Data.SimplePage', 'Required'=>true, 'AllowList'=>false));
		
	}

	public function run(){
		if(!parent::run()){
			return false;
		}
		
		//$store = $this->getInputValue('Store');
		$store = false;
		if(!$store){
			$store = $this->getKernelStore();
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
					'Value'=>'Modules.Website.Data.SimplePage'
				)	
			)
		);
		
		$page = DataClassLoader::createInstance('Modules.Website.Data.SimplePage');
		
		$conditions = DataClassLoader::createInstance('Kernel.Data.Primitive.ConditionGroup', $params);
		
		$retPage = $store->findOne($page, $conditions);
		
		if(!$retPage){
			$retPage = $page;
			$html = DataClassLoader::createInstance('Kernel.Data.Primitive.String', '<h1>404: Page Not Found</h1>');
			$retPage->setValue('WebsitePage', $retPage);
			
			$this->setOutputValue('PageLoaded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));
			$this->setOutputValue('PageNotLoaded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
		}else{
			$this->setOutputValue('PageLoaded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
			$this->setOutputValue('PageNotLoaded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));
		}
		
		$this->setOutputValue('WebsitePage', $retPage);
		
		$this->completeTask();
	}
}
?>