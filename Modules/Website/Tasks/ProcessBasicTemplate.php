<?php
//this is a glorified wrapper for Kernel.Tasks.Data.String.ProcessKeyValueString
// simply because i think people will expect a task like this to exist within the website module
//even though it does exactly the same thing as it's parent class. Cheers. 
class ModulesWebsiteTasksProcessBasicTemplate extends KernelTasksTask{
	public function __construct($config){
		parent::__construct($config);
		
		$this->_ClassName = 'Modules.Website.Tasks.ProcessBasicTemplate';
		$this->_ClassTitle='Website - Process Basic Template';
		$this->_ClassDescription = 'Processes a HTML String with basic templates using key/value pairs.  Templates are in the format of "{item Name}"';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '1.0.0';
		
		$this->inputs['Page'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Domain', 'Type'=>'Modules.Website.Data.SimplePage', 'Required'=>true, 'AllowList'=>false));
		
		//outputs
		$this->outputs['PageProcessed'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'HTML', 'Type'=>'Kernel.Data.Primitive.Boolean'));
		$this->outputs['HTML'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'HTML', 'Type'=>'Kernel.Data.Primitive.String'));
		
	}
	
	public function run(){
		if(!parent::run()){
			return false;
		}
		
		$page = $this->getInputValue('Page');
		
		$title = $page->getValue('Title');
		$header = $page->getValue('Header');
		$footer = $page->getValue('Footer');
		$content = $page->getValue('Content');
		
		$titleString = 'Error';
		$headerString = '';
		$contentString = '';
		$footerString = '';
		
		if($title){
			$titleString = $title->getValue();	
		}
		
		if($header){
			$header = $header->getValue('HTML');
			$headerString = $header->getValue();
			$headerString = str_replace('{Title}', $titleString, $headerString);	
		}
		
		if($footer){
			$footer = $footer->getValue('HTML');
			$footerString = $footer->getValue();
		}
		
		if($content){
			$contentString = $content->getValue();	
		}
			
		$htmlString = $headerString;
		$htmlString.=$contentString;
		$htmlString.=$footerString;
		
		$this->setOutputValue('PageProcessed', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
		$this->setOutputValue('HTML', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $htmlString));
		
		$this->completeTask();
	}
}
?>