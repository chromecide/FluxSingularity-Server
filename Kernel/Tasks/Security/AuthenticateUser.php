<?php
class KernelTasksSecurityAuthenticateUser extends KernelTasksTask{
	
	public function __construct($data){
		parent::__construct(false);
		
		$this->_ClassName = 'Kernel.Tasks.Security.AuthenticateUser';
		$this->_ClassTitle='Authenticate a Flux Singularity User';
		$this->_ClassDescription = 'Authenticate a User agianst a Supplied Data Source';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.8.0';
		
		$sourceDef = array('Name'=>'Source', 'Type'=>'Kernel.Data.DataStore', 'Required'=>true, 'AllowList'=>false);
		
		$usernameDef = array('Name'=>'Username', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false);
		
		$passwordDef = array('Name'=>'Password', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false);
		
		$this->inputs['Store'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', $sourceDef);
		$this->inputs['Username'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', $usernameDef);
		$this->inputs['Password'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', $passwordDef);
		
		$userDef = array('Name'=>'Username', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>false);
		$authSucceededDef = array('Name'=>'Authentication Succeeded', 'Type'=>'Kernel.Data.Primitive.Boolean', 'Required'=>true);
		$authFailedDef = array('Name'=>'Authentication Failed', 'Type'=>'Kernel.Data.Primitive.Boolean', 'Required'=>true);
		$authMessageDef = array('Name'=>'Authentication Message', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>false);
		
		$this->outputs['User'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', $userDef);
		$this->outputs['AuthenticationSucceeded'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', $authSucceededDef);
		$this->outputs['AuthenticationFailed'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', $authFailedDef);
		$this->outputs['AuthenticationMessage'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', $authMessageDef);
		
	}
	
	public function runTask(){
		if(!parent::runTask()){
			return false;
		}
		
		$userName = $this->getTaskInput('Username');
		$password = $this->getTaskInput('Password');
		
		$params = array(
			'Type'=>'AND',
			'Conditions'=>array(
				array(
					'Attribute'=>'Username',
					'Operator'=>'=',
					'Value'=>$userName
				),
				array(
					'Attribute'=>'Password',
					'Operator'=>'=',
					'Value'=>$password
				)	
			)
		);
		
		$conditionGroup = DataClassLoader::createInstance('Kernel.Data.Primitive.ConditionGroup', $params);
		
		$store = $this->getTaskInput('Store');
		
		
		if(!$store){
			$store = getKernelStore();	
		}
		
		$qUser = DataClassLoader::createInstance('Kernel.Data.Security.User');
		
		$user = $store->findOne($qUser, $conditionGroup);
		print_r($user);
		if($user){
			$this->setTaskOutput('User', $user);
			$this->setTaskOutput('AuthenticationFailed', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));
			$this->setTaskOutput('AuthenticationSucceeded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
			$this->setTaskOutput('AuthenticationMessage', DataClassLoader::createInstance('Kernel.Data.Primitive.String', 'Authentication Successful'));
		}else{
			$this->setTaskOutput('AuthenticationFailed', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
			$this->setTaskOutput('AuthenticationSucceeded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));
			$this->setTaskOutput('AuthenticationMessage', DataClassLoader::createInstance('Kernel.Data.Primitive.String', 'Invalid Username or Password'));
		}
		
		parent::completeTask();
	}
	
}
?>