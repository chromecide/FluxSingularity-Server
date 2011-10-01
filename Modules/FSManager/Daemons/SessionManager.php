<?php
class ModulesFSManagerDaemonsSessionManager extends KernelDaemonsDaemon{
	public function __construct(){
		
	}
	
	public function run(){
		/**
		 * Needs to handle:
		 * 
		 * 		- Expiring old sessions that are not set to be remembered
		 * 		- 
		 */		
		
		
		//load an FSManager Message
		$query = DataClassLoader::createInstance('Modules.FSManager.Data.Session');
		
		$conditions = DataClassLoader::createInstance('Kernel.Data.Primitive.List');
		
		
		$expiryThresh = strtotime('-30 seconds');
		$idleThresh = strtotime(-'5 mins');
		
		$expiryThreshEnt = DataClassLoader::createInstance('Kernel.Data.Primitive.DateTime', $expiryThresh);
		//print "Expiry Time: $expiryThresh\n";
		$timeArray = array('Attribute'=>'KernelModifiedDate', 'Operator'=>'<', 'Value'=>$expiryThreshEnt, 'Continuation'=>'END');
		$timeCondition = DataClassLoader::createInstance('Kernel.Data.Primitive.Condition', $timeArray);
		
		
		$conditions->addItem($timeCondition);
		
		$sessions = $query->find($conditions);
		
		if($sessions){
			$itemCount = $sessions->getValue('count');
			if($itemCount>0){
				for($i=0; $i<$itemCount; $i++){
					$session = $sessions->getItem($i);
					if($session->remove()){
						$messageConditions = DataClassLoader::createInstance('Kernel.Data.Primitive.List');
						//remove any remaining session messages
						$sessionIDCondition = DataClassLoader::createInstance('Kernel.Data.Primitive.Condition');
						$sessionIDCondition->set('Attribute', DataClassLoader::createInstance('Kernel.Data.Primitive.String', 'SessionID'));
						$sessionIDCondition->set('Operator', DataClassLoader::createInstance('Kernel.Data.Primitive.String', '=='));
						$sessionIDCondition->set('Value', $session->get('KernelID'));
						$sessionIDCondition->set('Continuation', DataClassLoader::createInstance('Kernel.Data.Primitive.String', 'END'));
						
						$messageConditions->addItem($sessionIDCondition);
						
						$msgQry = DataClassLoader::createInstance('Modules.FSManager.Data.Message');
						
						$messages = $msgQry->find($messageConditions);
						
						$msgCount = $messages->get('count');
						if($msgCount>0){
							for($j=0;$j<$msgCount;$j++){
								$messageItem = $messages->getItem($j);
								$messageItem->remove();
							}
						}
					}
				}
			}
		}else{
			System_Daemon::info('No Sessions Found');
		}
		
		//TODO
		//DELETE SESSION MESSAGES
		//CREATE "YOUR SESSION HAS EXPIRED" MESSAGE - how do i do that when the session and messages have been deleted
		
		
		
		
		
		/*
		 
		$rememberCondition = DataClassLoader::createInstance('Kernel.Data.Primitive.Condition');
		$rememberCondition->set('Attribute', DataClassLoader::createInstance('Kernel.Data.Primitive.String','RememberSession'));
		$rememberCondition->set('Operator', DataClassLoader::createInstance('Kernel.Data.Primitive.String','='));
		$rememberCondition->set('Value', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));
		$rememberCondition->set('Continuation', DataClassLoader::createInstance('Kernel.Data.Primitive.String','END'));
		
		*/
		
		
		return true;
	}
}