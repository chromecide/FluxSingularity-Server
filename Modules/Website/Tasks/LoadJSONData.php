<?php

class ModulesWebsiteTasksLoadJSONData extends KernelTasksTask{
	public function __construct(){
		parent::__construct();
		
		$this->_ClassName = 'Modules.Website.Tasks.LoadJSONData';
		$this->_ClassTitle='Load JSON Data';
		$this->_ClassDescription = 'Loads the JSON Data from a Web Request';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com>';
		$this->_ClassVersion = '0.0.4';

		//Inputs

		//Outputs
		$this->outputs['DataLoaded'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Data Loaded', 'Type'=>'Kernel.Data.Primitive.Boolean'));
		$this->outputs['DataNotLoaded'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'No Data Loaded', 'Type'=>'Kernel.Data.Primitive.Boolean'));
		$this->outputs['Count'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Data Item Count', 'Type'=>'Kernel.Data.Primitive.Number'));
		$this->outputs['Data'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Data List', 'Type'=>'Kernel.Data.Primitive.NamedList', 'AllowList'=>true));
	}

	public function run(){
		if(!parent::run()){
			return false;
		}
		//Defaults

		//Load Inputs

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
			$itemsLoaded=true;
			for($i=0;$i<count($input);$i++){
				foreach($input as $key=>$value){
					if(is_object($value)){
						$list->addItem($key, DataClassLoader::createInstance('Kernel.Data.Primitive.NamedList', $value));
					}else{
						$list->addItem($key, DataClassLoader::createInstance('Kernel.Data.Primitive.String', $value));	
					}
					
				}
				$finalList->addItem($list);
				$list = DataClassLoader::createInstance('Kernel.Data.Primitive.NamedList');	
			}
		}else{
			$itemsLoaded = true;
			foreach($input as $key=>$value){
				$list->addItem($key, DataClassLoader::createInstance('Kernel.Data.Primitive.String', $value));
			}
			$finalList->addItem($list);
		}
		
		$this->setOutputValue('DataLoaded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', $itemsLoaded));
		$this->setOutputValue('DataNotLoaded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', !$itemsLoaded));
		
		
		$this->setOutputValue('Data', $finalList);
		
		$this->completeTask();
	}
}
?>