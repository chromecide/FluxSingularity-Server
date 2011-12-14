<?php

class ModulesFSManagerTasksLoadRouterMessage extends KernelTasksTask{
	public function __construct(){
		parent::__construct(false);

		
		$this->_ClassName = 'Modules.FSMansger.Tasks.LoadRouterMessage';
		$this->_ClassTitle='Load Router Message';
		$this->_ClassDescription = 'Loads a Router Message';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com>';
		$this->_ClassVersion = '0.0.4';

		//Inputs

		//Outputs
		$this->outputs['MessageLoaded'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Messages Loaded', 'Type'=>'Kernel.Data.Primitive.Boolean'));
		$this->outputs['MessageNotLoaded'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'No Messages Loaded', 'Type'=>'Kernel.Data.Primitive.Boolean'));
		$this->outputs['Count'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Data Item Count', 'Type'=>'Kernel.Data.Primitive.Number'));
		$this->outputs['Messages'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Message List', 'Type'=>'Kernel.Data.Primitive.List', 'AllowList'=>true));

	}

	public function run(){
		if(!parent::run()){
			return false;
		}
		
		//Load the GET Data into a named list
		$itemsLoaded = false;
		$listCount = DataClassLoader::createInstance('Kernel.Data.Primitive.Number');
		$list = DataClassLoader::createInstance('Kernel.Data.Primitive.NamedList');
		$finalList = DataClassLoader::createInstance('Kernel.Data.Primitive.List');
		$jsonStr = file_get_contents('php://input');
		
		if($jsonStr){
			$input = json_decode($jsonStr);
		}
		
		if(is_array($input)){
			for($i=0;$i<count($input);$i++){
				$message = DataClassLoader::createInstance('Modules.FSManager.Data.Message', $input[$i]);
				
				$finalList->addItem($message);
				$itemsLoaded=true;	
			}
		}else{
			$message = DataClassLoader::createInstance('Modules.FSManager.Data.Message', $input);
			$message->setValue('Status', DataClassLoader::createInstance('Kernel.Data.Primitive.String', 'Processed'));
			$finalList->addItem($message);
			$itemsLoaded = true;		
		}
		
		$this->setOutputValue('MessageLoaded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', $itemsLoaded));
		$this->setOutputValue('MessageNotLoaded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', !$itemsLoaded));
		
		$this->setOutputValue('Messages', $finalList);
		return $this->completeTask();
	}
}
?>