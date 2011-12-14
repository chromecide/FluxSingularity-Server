<?php
class ModulesWebsiteTasksLoadPOSTData extends KernelTasksTask{
	public function __construct(){
		parent::__construct(false);

		$this->_ClassName = 'Modules.Website.Tasks.LoadPOSTData';
		$this->_ClassTitle='Load POST Data';
		$this->_ClassDescription = 'Loads the POST Data from a Web Request';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com>';
		$this->_ClassVersion = '0.0.4';

		//Inputs

		//Outputs
		$this->outputs['DataLoaded'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Data Loaded', 'Type'=>'Kernel.Data.Primitive.Boolean'));
		$this->outputs['DataNotLoaded'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'No Data Loaded', 'Type'=>'Kernel.Data.Primitive.Boolean'));
		$this->outputs['Count'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Data Item Count', 'Type'=>'Kernel.Data.Primitive.Number'));
		$this->outputs['Data'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Data List', 'Type'=>'Kernel.Data.Primitive.NamedList'));
		
	}

	public function run(){
		if(!parent::run()){
			return false;
		}
		//Defaults

		//Load Inputs

		//Load the POST Data into a named list
		$itemsLoaded = false;
		
		$listCount = DataClassLoader::createInstance('Kernel.Data.Primitive.Number');
		$list = DataClassLoader::createInstance('Kernel.Data.Primitive.NamedList');
		if(count($_POST)>0){
			foreach($_POST as $key=>$value){
				$list->addItem($key, DataClassLoader::createInstance('Kernel.Data.Primitive.String', $value));
			}
			$itemsLoaded = true;
			$listCount->setValue(count($_POST));
		}else{
			$listCount->setValue(0);
		}
		
		$this->setOutputValue('DataLoaded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', $itemsLoaded));
		$this->setOutputValue('DataNotLoaded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', !$itemsLoaded));
		$this->setOutputValue('Count', $listCount);
		$this->setOutputValue('Data', $list);
		
		return $this->completeTask();
	}
}
?>