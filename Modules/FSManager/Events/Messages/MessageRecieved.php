<?php 

class ModulesFSManagerEventsMessagesMessageRecieved extends KernelEventsEvent{
	public function __construct($data){
		parent::__construct($data);
		
		$sourceId = $_SESSION['FSManager.SourceID'];
		$source = DataClassLoader::createInstance('Kernel.Data.Messages.MessageSource');
		
		//if there is a source id, load it, otherwise create it
		if($sourceId){
			$source = DataClassLoader::createInstance('Kernel.Data.Messages.MessageSource');
		}else{
			//create a new session source
			$createSourceTask = DataClassLoader::createInstance('Modules.FSManager.Tasks.CreateSessionSource');
			$createSourceTask->setTaskInput('Enabled', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
			$createSourceTask->setTaskInput('SessionID', DataClassLoader::createInstance('Kernel.Data.Primitive.String', session_id()));
		}
		//if a destination id was supplied, load it otherwise create it
		
		
		$this->outputs['Message'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Result', 'Type'=>'Kernel.Data.Messages.Message'));
		
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
		print_r($input);
		/*
		if($input->ApplicationID){
			$message->setValue('ApplicationID', new KernelDataPrimitiveString($input->ApplicationID));
		}
		if($input->MessageID){
			$message->setValue('MessageID', new KernelDataPrimitiveString($input->MessageID));
		}
		if($input->Module){
			$message->setValue('Module', new KernelDataPrimitiveString($input->Module));
		}
		if($input->Process){
			$message->setValue('Process', new KernelDataPrimitiveString($input->Process));	
		}
		if($input->Status){
			$message->setValue('Status', new KernelDataPrimitiveString($input->Status));	
		}
		if($input->Parameters){
			$params = new KernelDataPrimitiveNamedList($input->Parameters);
			$message->setValue('Parameters', $params);
		}else{
		}*/
	
		if($input->SessionID){
			$message->set('SessionID', new KernelDataPrimitiveString($input->SessionID));	
		}
		
		$session = new ModulesFSManagerDataSession();
		
		$user = new KernelDataEntityUser();
		
		$this->setOutputValue('Message', $message);
		$this->setOutputValue('User', $user);
		$this->setOutputValue('Session', $session);
	}
	
	public function fire(){
		echo 'firing event';
		parent::fire();
	}
}

?>