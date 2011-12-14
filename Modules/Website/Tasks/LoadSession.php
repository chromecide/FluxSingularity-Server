<?php
class ModulesWebsiteTasksLoadSession extends KernelTasksTask{
	public function __construct(){
		parent::__construct(false);

		$this->_ClassName = 'Modules.Website.Tasks.LoadSession';
		$this->_ClassTitle='Load Session Data';
		$this->_ClassDescription = 'Loads the SESSION Data from a Web Request';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com>';
		$this->_ClassVersion = '0.0.4';

		//Inputs

		//Outputs
		$this->outputs['SessionLoaded'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Session Loaded', 'Type'=>'Kernel.Data.Primitive.Boolean'));
		$this->outputs['SessionNotLoaded'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'No Session Loaded', 'Type'=>'Kernel.Data.Primitive.Boolean'));
		$this->outputs['Session'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Data Item Count', 'Type'=>'Kernel.Data.Primitive.Number'));
	}

	public function run(){
		if(!parent::run()){
			return false;
		}
		//Defaults

		//Load Inputs
		$session = DataClassLoader::createInstance('Modules.Website.Data.Session');
		$sessionLoaded = false;
		$id = $session->getValue('SessionID');
		
		$params = array(
			'Type'=>'AND',
			'Conditions'=>array(
				array(
					'Attribute'=>'SessionID',
					'Operator'=>'=',
					'Value'=>$id
				)	
			)
		);
		
		$conditionGroup = DataClassLoader::createInstance('Kernel.Data.Primitive.ConditionGroup', $params);
		
		if($sessionObj = $session->findOne($conditionGroup)){
			$sessionLoaded = true;
			$session = $sessionObj;
		}else{
			if(!$session->save()){
				//print_r($session->validationErrors);
			}
		}
		
		$this->setOutputValue('SessionLoaded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', $sessionLoaded));
		$this->setOutputValue('SessionNotLoaded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', !$sessionLoaded));
		$this->setOutputValue('Session', $session);
		
		$this->completeTask();
	}
}
?>