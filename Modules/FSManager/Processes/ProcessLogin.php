<?php
class ModulesWebsiteProcessesProcessLogin extends KernelProcessesProcess{
	public function __construct($config){
		
		parent::__construct(false);
		
		$this->_ClassName = 'Modules.Website.Processes.ProcessLogin';
		$this->_ClassTitle='Process a HTML Form Login';
		$this->_ClassDescription = 'Processes a Basic HTML Login form.  The Field names must be "Username" and "Password"';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com>';
		$this->_ClassVersion = '1.0.0';
		
		//Inputs
		$this->inputs['Username'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Username', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		$this->inputs['Password'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Password', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		
		//outputs
		$this->outputs['UserAuthenticated'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'User Authenticated', 'Type'=>'Kernel.Data.Primitive.Boolean'));
		$this->outputs['UserNotAuthenticated'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'User Not Authenticated', 'Type'=>'Kernel.Data.Primitive.Boolean'));
		$this->outputs['Message'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Message', 'Type'=>'Kernel.Data.Primitive.String '));
		$this->outputs['User'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'User', 'Type'=>'Modules.FSManager.Data.User'));
		
		$this->parseConfig($config);
		
		$this->buildTaskMap();
	}
	
	public function buildTaskMap(){
		$process = array(
			'LocalData'=>array(
				
			),
			'Tasks'=>array(
				'AuthenticateUser'=>'Kernel.Tasks.Security.AuthenticateUser',
				'LoadUser'=>'Modules.FSManager.Tasks.AuthenticateUser'
			),
			'TaskMap'=>array(
				'LocalData'=>array(),
				'Inputs'=>array(
					'Enabled'=>array(
						'AuthenticateUser.Enabled'
					),
					'Username'=>array(
						'AuthenticateUser.Username'
					),
					'Password'=>array(
						'AuthenticateUser.Password'
					)
				),
				'AuthenticateUser'=>array(
					'AuthenticationSucceeded'=>array(
						'Outputs.UserAuthenticated',
						'LoadUser.Enabled'
					),
					'AuthenticationFailed'=>array(
						'Outputs.UserNotAuthenticated'
					),
					'AuthenticationMessage'=>array(
						'Outputs.Message'
					),
					'User'=>array(
						'LoadUser.UserID'
					)
				)
			)
		);

		$this->parseDefinition($process);
	}
}
?>