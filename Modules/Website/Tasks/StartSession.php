<?php
class ModulesWebsiteTasksStartSession extends KernelTasksTask{
	public function __construct(){
		parent::__construct();
		
		$this->_ClassName = 'Modules.Website.Tasks.StartSession';
		$this->_ClassTitle='Start Web Session';
		$this->_ClassDescription = 'Starts a Web Session';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '1.0.0';

		//Inputs
		$this->inputs['SessionID'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'SessionID', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		$this->inputs['RememberSession'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'RememberSession', 'Type'=>'Kernel.Data.Primitive.Boolean', 'Required'=>false, 'AllowList'=>false));
		
		//Outputs
		$this->outputs['Session'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Session', 'Type'=>'Modules.Website.Data.Session'));
		$this->outputs['Session'] = array('Session', 'Modules.FSManager.Data.Session');
		$this->outputs['SessionCreated'] = array('SessionCreated', 'Kernel.Data.Primitive.Boolean');
		$this->outputs['Session'] = array('SessionNotCreated', 'Kernel.Data.Primitive.Boolean');
		
		//Defaults
		
		$this->setTaskInput('RememberSession', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));
	}

	public function runTask(){
		//System_Daemon::info('    Starting Task: CreateSession');
		//Load Inputs
		$RememberSession = $this->getTaskInput('RememberSession');

		$session = DataClassLoader::createInstance('Modules.FSManager.Data.Session');
		$session->set('RememberSession', $RememberSession);
		$session->save();
		
		$this->setTaskOutput('Session', $session);
		$this->setTaskOutput('SessionCreated', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
		$this->setTaskOutput('SessionNotCreated', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));
		
		parent::runTask();
	}
}
?>