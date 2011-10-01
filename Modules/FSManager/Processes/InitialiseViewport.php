<?php
class ModulesFSManagerProcessesInitialiseViewport extends KernelProcessesProcess{
	public function __construct(){
		parent::__construct();
		$this->KernelClass='Modules.FSManager.Processes.InitialiseSession';
		$this->title='Initialise Session';
		$this->description='Initialise an FSManager User Session';
		$this->author='Justin Pradier <justin.pradier@fluxsingularity.com>';

		//Inputs
		$this->inputs['SessionID'] = array('SessionID', 'Kernel.Data.Primitive.String', true);
		
		//Outputs
		$this->outputs['Viewport'] = array('Viewport', 'Modules.FSManager.Data.Viewport');
		$this->buildTaskMap();
	}

	public function buildTaskMap(){
		//build the tasks that are part to need for this process
		$findSessionViewportTask = DataClassLoader::createInstance('Kernel.Tasks.Data.SearchEntities');
		$createDefaultViewport = DataClassLoader::createInstance('Modules.FSManager.Tasks.CreateDefaultViewport');
		
		//create any "hard coded" data
		$sessionType = DataClassLoader::createInstance('Kernel.Data.Primitive.String', 'Modules.FSManager.Data.Session');
		$this->setTokenData('LoadSessionViewport', 'Entity', $sessionType);
		
		//create the mapping between tasks
		$this->tasks['LoadSessionViewport'] = $findSessionViewportTask;
		
		
		
		$findSessionConditions = DataClassLoader::createInstance('Kernel.Data.Primitive.List');
		
		$findSessTypeCondition = DataClassLoader::createInstance('Kernel.Data.Primitive.Condition');
		$findSessTypeCondition->set('Attribute', DataClassLoader::createInstance('Kernel.Data.Primitive.String', ''));
		
	}
}
?>