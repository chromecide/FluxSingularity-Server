<?php
class ModulesWebsiteTasksLoadGETData extends KernelTasksTask{
	public function __construct(){
		parent::__construct();

		$this->_ClassName = 'Modules.Website.Tasks.LoadGETData';
		$this->_ClassTitle='Load GET Data';
		$this->_ClassDescription = 'Loads the GET Data from a Web Request';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com>';
		$this->_ClassVersion = '0.0.4';

		//Inputs

		//Outputs
		$this->inputs['DataLoaded'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Data Loaded', 'Type'=>'Kernel.Data.Primitive.Boolean'));
		$this->inputs['DataNotLoaded'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'No Data Loaded', 'Type'=>'Kernel.Data.Primitive.Boolean'));
		$this->inputs['Count'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Data Item Count', 'Type'=>'Kernel.Data.Primitive.Number'));
		$this->inputs['Data'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Data List', 'Type'=>'Kernel.Data.Primitive.NamedList'));
		
	}

	public function runTask(){
		if(!parent::runTask()){
			return false;
		}
		//Defaults

		//Load Inputs

		//Load the GET Data into a named list
		$itemsLoaded = false;
		$listCount = DataClassLoader::createInstance('Kernel.Data.Primitive.Number');
		$list = DataClassLoader::createInstance('Kernel.Data.Primitive.NamedList');
		if(count($_GET)>0){
			foreach($_GET as $key=>$value){
				$list->addItem($key, DataClassLoader::createInstance('Kernel.Data.Primitive.String', $value));
			}
			$itemsLoaded = true;
			$listCount->setValue(count($_GET));	
		}else{
			$listCount->setValue(0);
		}
		
		$this->setOutputValue('DataLoaded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', $itemsLoaded));
		$this->setOutputValue('DataNotLoaded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', !$itemsLoaded));
		$this->setOutputValue('Count', $listCount);
		$this->setOutputValue('Data', $list);
		
		$this->completeTask();
	}
}
?>