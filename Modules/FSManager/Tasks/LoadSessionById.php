<?php
class ModulesFSManagerTasksLoadSessionById extends KernelTasksTask{
	public function __construct(){
		parent::__construct();
		$this->kernelClass='Modules.FSManager.Tasks.LoadSessionById';
		$this->title='Load Session By ID';
		$this->description='Loads a User session object using the supplied id';
		$this->author='Justin Pradier <justin.pradier@fluxsingularity.com>';

		//Inputs

		$this->inputs['SessionID'] = array('SessionID', 'Kernel.Data.Primitive.String', true);
		//Outputs

		$this->outputs['Session'] = array('Session', 'Modules.FSManager.Data.Session');
		$this->outputs['SessionFound'] = array('Session', 'Kernel.Data.Primitive.Boolean');
		$this->outputs['SessionNotFound'] = array('Session', 'Kernel.Data.Primitive.Boolean');
	}

	public function runTask(){
		
		System_Daemon::info('    Starting Task: LoadSession');
		//Defaults

		//Load Inputs
		$SessionID = $this->getTaskInput('SessionID');

		$qry = DataClassLoader::createInstance('Modules.FSManager.Data.Session');
		
		$sessionObj = $qry->loadById($SessionID);
		
		if($sessionObj){
			$this->setTaskOutput('Session', $sessionObj);
			$this->setTaskOutput('SessionFound', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
			$this->setTaskOutput('SessionNotFound', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));
		}else{
			$this->setTaskOutput('SessionFound', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));
			$this->setTaskOutput('SessionNotFound', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
		}
		
		parent::runTask();
	}
}
?>