<?php
class ModulesWebsiteProcessesValidateSession extends KernelProcessesProcess{
	public function __construct($config){
		
		parent::__construct(false);
		
		$this->_ClassName = 'Modules.Website.Processes.ValidateSession';
		$this->_ClassTitle='Validates a Session';
		$this->_ClassDescription = 'Validate a Website Session';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com>';
		$this->_ClassVersion = '1.0.0';
		
		//Inputs
		//$this->inputs['Domain'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Domain', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		//$this->inputs['PagePath'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'PagePath', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		
		//outputs
		$this->outputs['SessionValid'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Session Valid', 'Type'=>'Kernel.Data.Primitive.Boolean'));
		$this->outputs['SessionNotValid'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Session Not Valid', 'Type'=>'Kernel.Data.Primitive.Boolean'));
		$this->outputs['Session'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'HTML', 'Type'=>'Modules.Website.Data.Session'));
		
		$this->parseConfig($config);
		
		$this->buildTaskMap();
	}
	
	public function buildTaskMap(){
		$process = array(
			'LocalData'=>array(
				'SessionID'=>DataClassLoader::createInstance('Kernel.Data.Primitive.String', session_id())
			),
			'Tasks'=>array(
				'LoadSession'=>'Modules.Website.Tasks.LoadSession',
				'CreateSession'=>'Modules.Website.Tasks.StartSession',
				'IfSessionExists'=>'Kernel.Tasks.Logic.If'
			),
			'TaskMap'=>array(
				'LocalData'=>array(
					'SessionID'=>array(
						'LoadSessionByID.ID'
					)
				),
				'Inputs'=>array(
					'Enabled'=>array(
						'LoadSession.Enabled'
					)
				)
			)
		);

		$this->parseDefinition($process);
	}
}
?>