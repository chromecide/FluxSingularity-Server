<?php
class ModulesFSManagerTasksCreateSessionSource extends KernelTasksTask{
	public function __construct(){
		parent::__construct();

		$this->_ClassName = 'Modules.FSManager.Tasks.CreateSessionSource';
		$this->_ClassTitle='Base Message Source Object';
		$this->_ClassDescription = 'Used to handle message passing throught the system';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.5.0';

		//Inputs
		$this->inputs['Source'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Source', 'Type'=>'Kernel.Data.DataStore', 'Required'=>false, 'AllowList'=>false));
		$this->inputs['SessionID'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'SessionID', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		$this->inputs['Owner'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Owner', 'Type'=>'Kernel.Data.Security.User', 'Required'=>true, 'AllowList'=>false));
		
		//Outputs
		$this->outputs['Source'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Source', 'Type'=>'Kernel.Data.Messages.MessageSource'));
		$this->outputs['SourceCreated'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'SourceCreated', 'Type'=>'Kernel.Data.Primitive.Boolean'));
		$this->outputs['SourceNotCreated'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'SourceNotCreated', 'Type'=>'Kernel.Data.Primitive.Boolean'));
	
	}

	public function runTask(){
		if(!parent::runTask()){
			return false;
		}	
		
		$messageSource = DataClassLoader::createInstance('Kernel.Data.Messages.MessageSource');
		$created = false;
		
		$source->setValue('Application', DataClassLoader::createInstance("Kernel.Data.Primitive.String", $_SERVER['HTTP_HOST']));
		$source->setValue('ApplicationChildID', $this->getTaskInput('SessionID'));
		$source->setValue('Owner', $this->getTaskInput('Owner'));
		
		if($source->save()){
			$created = true;
		}
		
		if($created){
			$this->setTaskOutput('SourceCreated', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
			$this->setTaskOutput('SourceNotCreated', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));
		}else{
			$this->setTaskOutput('SourceCreated', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));
			$this->setTaskOutput('SourceNotCreated', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
		}

		return $this->completeTask();
	}
}
?>