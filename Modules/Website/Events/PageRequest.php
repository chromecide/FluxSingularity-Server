<?php
class ModulesWebsiteEventsPageRequest extends KernelEventsEvent{
	public function __construct(){
		parent::__construct();
		
		$this->outputs['Domain'] = array('Domain', 'Kernel.Data.Primtive.String', true, false);
		$this->outputs['PagePath'] = array('PagePath', 'Kernel.Data.Primtive.String', false, false);
		$this->outputs['PostData'] = array('PostData', 'Kernel.Data.Primitive.String', false, true);
		$this->outputs['GetData'] = array('GetData', 'Kernel.Data.Primitive.String', false, true);
		
		$this->setOutputValue('Domain', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $_SERVER['HTTP_HOST']));
		
		$this->dataClassName = 'Modules.Website.Events.PageRequest';
	}
	
	public function fire(){
		
		$listenerObj = DataClassLoader::createInstance('Kernel.Data.Event.Listener');
		
		$domain = $this->getOutputValue('Domain');
		$pagePath = $this->getOutputValue('PagePath');
		$postData = $this->getOutputValue('PostData');
		$getData = $this->getOutputValue('GetData');
		
		$conditions = DataClassLoader::createInstance('Kernel.Data.Primitive.List');
		$conditions->setType('Kernel.Data.Primitive.TaskCondition');
		
		$domainCondition = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskCondition');
		$domainCondition->set('Attribute', DataClassLoader::createInstance('Kernel.Data.Primitive.String', 'Domain'));
		$domainCondition->set('Operator', DataClassLoader::createInstance('Kernel.Data.Primitive.String', '='));
		$domainCondition->set('Value', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $domain->get()));
		$domainCondition->set('Continuation', DataClassLoader::createInstance('Kernel.Data.Primitive.String', 'END'));
		
		$conditions->addItem($domainCondition);
		
		//echo __FILE__.' '.__LINE__.'<Br/>';
		//echo 'need to add support for task isReady and appropriate changes in the process functionality<br/><Br/>';
		
		//print_r($conditions);
		$params = array(
			'Enabled'=>DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true),
			'Event'=>DataClassLoader::createInstance('Kernel.Data.Primitive.String', $this->dataClassName),
			'Conditions'=>$conditions
		);
		
		$listeners = $listenerObj->find($params);
		parent::fire();
	}
}
?>