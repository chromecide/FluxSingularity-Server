<?php
class ModulesFSManagerDaemonsMessageProcessor extends KernelDaemonsDaemon{
	public function __construct(){
		
	}
	
	public function run(){
		//load an FSManager Message
		$query = DataClassLoader::createInstance('Modules.FSManager.Data.Message');
		
		//$params = array();
		//$params['Status'] = DataClassLoader::createInstance('Kernel.Data.Primitive.String', 'Pending');
		//$params['ApplicationID'] = DataClassLoader::createInstance('Kernel.Data.Primitive.String', 'FSManager');
		
		$statusArray = array('Attribute'=>'Status', 'Operator'=>'==', 'Value'=>'Pending', 'Continuation'=>'AND');
		$statusCondition = DataClassLoader::createInstance('Kernel.Data.Primitive.Condition', $statusArray);
		
		$appIDArray = array('Attribute'=>'ApplicationID', 'Operator'=>'==', 'Value'=>'FSManager', 'Continuation'=>'AND');
		$appIDCondition = DataClassLoader::createInstance('Kernel.Data.Primitive.Condition', $statusArray);
		
		$params = DataClassLoader::createInstance('Kernel.Data.Primitive.List');
		$params->addItem($appIDCondition);
		$params->addItem($statusCondition);
		
		$message = $query->findOne($params);
		
		if($message){
			
			//echo 'Module: '.$message->get('Module')->get().'<br/>';
			//echo 'Process: '.$message->get('Process')->get().'<br/>';
			//echo 'Status: '.$message->get('Status')->get().'<br/>';
			//echo 'ApplicationID: '.$message->get('ApplicationID')->get().'<br/>';
			//echo 'Module: '.$message->get('Module')->get().'<br/>';
			$params = $message->get('Parameters');
			
			//build the process object
			$processName = 'Modules.'.$message->get('Module')->getValue().'.Processes.'.$message->get('Process')->getValue();
			System_Daemon::info('Processing: '.$processName);
			//echo $processName.'<br/>';
			//run the process
			$process = DataClassLoader::createInstance($processName);
			
			if($process){
				//set the process inputs
				if($params && $params instanceof KernelDataPrimitiveNamedList){
					$processInputs = $process->getInputs();
					if(count($processInputs)>0){
						foreach($processInputs as $inputName=>$inputCfg){
							if($inputName=='Message'){
								$process->setProcessInput('Message', $message);
							}
							if($item = $params->getItem($inputName)){
								if($item){
									$process->setProcessInput($inputName, $item);
								}
							}
						}
					}
				}
				
				//run the process
				$process->run();
				//attach the process outputs to the responses collection of the message
				$outputs = $process->getOutputs();
				$response = DataClassLoader::createInstance('Kernel.Data.Primitive.NamedList');
				
				if(count($outputs)>0){
					foreach($outputs as $outputName=>$outputCfg){
						if($item = $process->getProcessOutput($outputName)){
							if($item){
								$response->addItem($outputName, $item);
							}
						}
					}
				}
				
				$message->set('Status', DataClassLoader::createInstance('Kernel.Data.Primitive.String','Processed'));
				$message->set('Responses', $response);
				$message->save();
			//save the message	
			}else{
				System_Daemon::notice('Unable to find Process');
				//echo 'process not found<br/>';
			}
			
		}else{
			System_Daemon::info('No Messages Found');
			//echo 'no messages found<br/>';
		}
		
	}
}