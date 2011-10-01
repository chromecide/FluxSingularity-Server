<?php
class ModulesFsmanagerTasksCreateRouterMessage extends KernelTasksTask{
	public function __construct(){
		parent::__construct();
		
		$this->_ClassName = 'Modules.FSManager.Tasks.CreateRouterMessage';
		$this->_ClassTitle='Create FSManager Router Message';
		$this->_ClassDescription = 'Creates a router message from JSON recieved via a HTTP Request';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '1.0.0';
		
		$this->outputs['Message'] = array('Definition', 'Kernel.Data.Messages.Message', true);
	}
	
	public function runTask(){
		if(!parent::runTask()){
			return false;
		}
		
		$message = DataClassLoader::createInstance('Kernel.Data.Messages.Message');
		
		switch($_SERVER['REQUEST_METHOD']){
			case 'GET': //load Messages
				$input = new stdClass();
				foreach($_GET as $key=>$value){
					$input->$key = $value;
				}
				break;
			case 'POST': //add/update
				$input = $_POST;
				if(!$input){
					$jsonStr = file_get_contents('php://input');
					
					if($jsonStr){
						$input = json_decode($jsonStr);
					}
				}
				break;
			default:
				break;
		}
		
		if($input->ApplicationID){
			$message->setValue('ApplicationID', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $input->ApplicationID));
		}
		if($input->MessageID){
			$message->setValue('MessageID', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $input->MessageID));
		}
		if($input->Module){
			$message->setValue('Module', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $input->Module));
		}
		if($input->Process){
			$message->setValue('Process', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $input->Process));	
		}
		if($input->Status){
			$message->setValue('Status', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $input->Status));	
		}
		if($input->Parameters){
			$params = DataClassLoader::createInstance('Kernel.Data.Primitive.NamedList', $input->Parameters);
			$message->setValue('Parameters', $params);
		}
	
		if($input->SessionID){
			$message->setValue('SessionID', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $input->SessionID));	
		}
		
		$this->setTaskOutput('Message', $message);
		
		$this->setTaskOutput('Succeeded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
		return $this->completeTask();
	}
	
	public function validateInputs(){
		parent::validateInupts();
	}
}
?>