<?php
class ModulesFSManagerTasksLoadDefaultViewport extends KernelTasksTask{
	public function __construct(){
		parent::__construct();
		$this->kernelClass='Modules.FSManager.Tasks.LoadDefaultViewport';
		$this->title='Load Default Viewport';
		$this->description='Load the Default Viewport';
		$this->author='Justin Pradier <justin.pradier@fluxsingularity.com>';

		//Inputs

		//Outputs
		$this->outputs['Viewport'] = array('Viewport', 'Modules.FSManager.Data.Viewport');
		
		//Defaults
	}
	
	public function runTask(){

		//Load Inputs
		
		$conditionList = DataClassLoader::createInstance('Kernel.Data.Primitive.List');
		
		$IDCondition = DataClassLoader::createInstance('Kernel.Data.Primitive.Condition');
		$IDCondition->set('Attribute', DataClassLoader::createInstance('Kernel.Data.Primitive.String', 'KernelID'));
		$IDCondition->set('Operator', DataClassLoader::createInstance('Kernel.Data.Primitive.String', '='));
		$IDCondition->set('Value', DataClassLoader::createInstance('Kernel.Data.Primitive.String', 'KernelID'));
		
		parent::runTask();
	}
}
?>