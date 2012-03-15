<?php
class ModulesFSManagerActionsAuthenticateUser extends KernelObject{
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->useDefinition('Object.Action');
		
		$this->setValue('ID','Modules.FSManager.Actions.AuthenticateUser');
		$this->setValue('Name','Authenticate an FSManager User');
		$this->setValue('Description', 'Prevents any more actions from being fired for the Event');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
		
		$this->addAttribute('Username', 'Object.String', true, false, true, 'User name');
		$this->addAttribute('Password', 'Object.String', true, false, true, 'Password');
		
		$this->addEvent('AuthenticationSucceeded', array('Source'=>'Modules.FSManager.Actions.AuthenticateUser'));
		$this->addEvent('AuthenticationFailed', array('Source'=>'Modules.FSManager.Actions.AuthenticateUser'));
	}
	
	public function run(&$inputObject){
		
		//$inputObject = $this->getValue('Input');
		$inputObject->addTrace('Modules.FSManager.Actions.AuthenticateUser', 'Starting Action');
		$userName = $inputObject->getValue('Username');
		$password = $inputObject->getValue('Password');
		
		$inputObject->addTrace('Modules.FSManager.Actions.AuthenticateUser', ' - Building Query');
		$userObject = new KernelObject();
		
		$userObject->setValue('Definitions', 'Object.Security.User');
		$userObject->setValue('Username', $userName);
		$userObject->setValue('Password', $password);
		
		$inputObject->addTrace('Modules.FSManager.Actions.AuthenticateUser', ' - Querying Data Source');
		
		if($userObject->findOne()){
			$_SESSION['FSManager_User_ID'] = $userObject->getValue('ID');
			return $this->fireEvent('AuthenticationSucceeded');
		}else{
			$inputObject->addTrace('Modules.FSManager.Actions.AuthenticateUser', ' - User not found');
			$inputObject->addError('Invalid Username or Password Supplied', $query);
			
			return $this->fireEvent('AuthenticationFailed');
		}
	}
	
	
}
?>