<?php
class ModulesFSManagerTasksLoadUser extends KernelTasksTask{
	public function __construct(){
		parent::__construct();

		$this->_ClassName = 'Modules.FSManager.Tasks.LoadUser';
		$this->_ClassTitle='Load FSManager User';
		$this->_ClassDescription = 'Load na FSManager User';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.5.0';

		//Inputs
		$this->inputs['Store'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Store', 'Type'=>'Kernel.Data.DataStore', 'Required'=>false, 'AllowList'=>false));
		//$this->inputs['UserID'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'UserID', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		
		//Outputs
		$this->outputs['User'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Source', 'Type'=>'Kernel.Data.Messages.MessageSource'));
		$this->outputs['UserLoaded'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'UserLoaded', 'Type'=>'Kernel.Data.Primitive.Boolean'));
		$this->outputs['UserNotLoaded'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'UserNotLoaded', 'Type'=>'Kernel.Data.Primitive.Boolean'));
	
	}

	public function runTask(){
		if(!parent::runTask()){
			return false;
		}	
		
		$store = $this->getTaskInput('Store');
		$userID = $_SESSION['UserID'];
		
		if(!$store){
			$user->setStore($store);
		}
		
		$loaded = false;
		
		if($userID){
			if($user->loadById(DataClassLoader::createInstance('Kernel.Data.Primitive.String', $userId))){
				$this->setTaskOutput('User', $user);
				$loaded = true;
			}	
		}
		
		if($loaded){
			$this->setTaskOutput('UserLoaded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
			$this->setTaskOutput('UserNotLoaded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));
		}else{
			$this->setTaskOutput('UserLoaded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));
			$this->setTaskOutput('UserNotLoaded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
		}

		return $this->completeTask();
	}
}
?>