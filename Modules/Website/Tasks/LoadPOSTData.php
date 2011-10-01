<?php
class ModulesWebsiteTasksLoadPOSTData extends KernelTasksTask{
	public function __construct(){
		parent::__construct();
		$this->kernelClass='Modules.Website.Tasks.LoadPOSTData';
		$this->title='Load GET Data';
		$this->description='Load GET Data';
		$this->author='Justin Pradier <justin.pradier@fluxsingularity.com>';

		//Inputs

		//Outputs
		$this->outputs['Count'] = array('Count', 'Kernel.Data.Primitive.Number');
		$this->outputs['Data'] = array('Data', 'Kernel.Data.Primitive.NamedList');
	}

	public function runTask(){
		//Defaults

		//Load Inputs

		//Load the GET Data into a named list
		$list = DataClassLoader::createInstance('Kernel.Data.Primitive.NamedList');
		
		foreach($_POST as $key=>$value){
			$list->addItem($key, DataClassLoader::createInstance('Kernel.Data.Primitive.String', $value));
		}
		
		$this->setTaskOutput('Count', count($_POST));
		$this->setTaskOutput('Data', $list);
		
		parent::runTask();
	}
}
?>