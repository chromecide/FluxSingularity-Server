<?php 
class ApplicationRouter{
	public $messages = array();
	
	public function __construct(){
		
	}
	
	public function queueMessage($message){
		if($this->validateMessage($message)){
			$this->messages[] = $message;
		}
	}
	
	public function validateMessage($message){
		return true;	
	}
	
	public function retrieveMessages($owner){
		
	}
	
	public function buildMessage($requestMessage){
		
		$qMessage = new ApplicationMessage();
		$qMessage->set('ApplicationID', $requestMessage->ApplicationID);
		$newID = new MongoId();
		$qMessage->set('MessageID', $requestMessage->MessageID?$requestMessage->MessageID:(String)$newID);
		$qMessage->set('ClassName', $requestMessage->ClassName?$requestMessage->ClassName:'Application.Message');
		$qMessage->set('Action', $requestMessage->Action?$requestMessage->Action:'findAsArray');
		$qMessage->set('Owner', $userID);
		$qMessage->set('Parameters', $requestMessage->Parameters);
		$qMessage->set('Status', $requestMessage->Status);
		$qMessage->set('Created', strtotime('now'));
		
		return $qMessage;
		//$router->queueMessage($qMessage);
	}
	public function processRequest(){
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
			
		if(!$input){
			return false;
		}
		
		$messages = array();
		
		if(is_array($input)){
			foreach($input as $message){
				$messageObj = $this->buildMessage($message);
				$messageObj->save();
				$this->queueMessage($messageObj);
			}
		}else{
			$messageObj = $this->buildMessage($input);
			$messageObj->save();
			$this->queueMessage($messageObj);
		}
		return $this->processQueue();
	}
	
	public function processQueue(){
		$returns = array();
		foreach ($this->messages as $index=>$message){
			$className = $message->get('ClassName');
			$classObjName = DataClassLoader::loadClass($className);
			
			if(class_exists($classObjName)){
				$newObj = new $classObjName();
			}else{
				$returns[] = array('could not find class name: '.$classObjName);
			}
			
			if($newObj){
				$action = $message->get('Action');
				$parameters = $message->get('Parameters');
				
				foreach($parameters as $key=>$value){
					$newObj->set($key, $value);
				}
				
				$ret = $newObj->$action($parameters);
				$message->set('Status', $this->incrementStatus($message->get('Status')));
				
				$message->set('Response', $ret);
				$message->save();
				if($ret!==false){
					if($message->get('ClassName')=='Application.Message'){
						foreach($ret as $message){
							$returns[] = $message;
						}
					}else{
						$returns[] = $message->toArray();	
					}
				}else{
					$returns[] = array('Error Processing Message');//echo 'error processing action';
				}
			}else{
				echo 'no object created';
			}
		}
		
		return $returns;
	}
	
	public function incrementStatus($status){
		switch($status){
			case 'New':
				return 'Pending';
				break;
			case 'Pending':
				return 'Processed';
				break;
			case 'Processed':
				return 'Closed';
				break;
			case '':
			default:
				return 'New';
				break;
		}
	}
}
?>