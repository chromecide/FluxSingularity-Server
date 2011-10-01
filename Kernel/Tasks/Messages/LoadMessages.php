<?php 
class KernelTasksMessagesLoadMessages extends KernelTasksTask{
	public function __construct(){
		$this->inputs['Queue'] = array('Queue', 'Kernel.Data.Messages.MessageQueue', false, true);
		$this->inputs['ClientID'] = array('ClientID', 'Kernel.Data.Primitive.String', false, true);
		$this->inputs['User'] = array('User', 'Kernel.Data.Security.User', false, true);
		$this->inputs['Module'] = array('Module', 'Kernel.Data.Primitive.String', false, true);
		$this->inputs['Process'] = array('Process', 'Kernel.Data.Primitive.String', false, true);
		
		$this->outputs['Messages'] = array('Messages', 'Kernel.Data.Messages.Message', true);
		
		parent::__construct();
	}
	
	public function runTask(){
		$Queue = $this->getTaskInput('Queue');
		$ClientID = $this->getTaskInput('ClientID');
		$User = $this->getTaskInput('User');
		$Module = $this->getTaskInput('Module');
		$Process = $this->getTaskInput('Process');
		
		$messageObj = DataClassLoader::createInstance('Kernel.Data.Messages.Message');
		
		$params = new stdClass();
		
		if($Queue){
			$params->Queue = $Queue;
		}
		if($ClientID){
			$params->ClientID = $ClientID;
		}
		if($User){
			$params->User = $User;
		}
		if($Module){
			$params->Module = $Module;
		}
		if($Process){
			$params->Process = $Process;
		}
		
		$messages = $messageObj->find($params);
		
		$ret = DataClassLoader::createInstance('Kernel.Data.Primitive.List');
		
		foreach($messages as $message){
			$ret->addItem($message);	
		}
		
		$this->setTaskOutput('Messages', $ret);
		$this->setTaskOutput('Completed', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
		
	}
}
?>