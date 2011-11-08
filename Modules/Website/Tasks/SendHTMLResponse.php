<?php 
class ModulesWebsiteTasksSendHTMLResponse extends KernelTasksTask{
	public function __construct(){
		parent::__construct(false);
		
		$this->_ClassName = 'Modules.Website.Tasks.SendHTMLResponse';
		$this->_ClassTitle='Send HTML Response';
		$this->_ClassDescription = 'Sends a HTML Response';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.8.0';
		
		$this->inputs['HTMLHeaders'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'HTML Headers', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>false, 'AllowList'=>true));
		$this->inputs['HTMLString'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'HTML String', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true));
	}
	
	public function run(){
		
		if(!parent::run()){
			return false;
		}
		
		$html = $this->getInputValue('HTMLString');
		
		echo $html->getValue();
		
		return $this->completeTask();
	}
}
?>