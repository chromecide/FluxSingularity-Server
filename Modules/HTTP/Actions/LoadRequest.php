<?php
class ModulesHTTPActionsLoadRequest extends KernelObject{
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->useDefinition('Object.Action');
		
		$this->setValue('ID','Modules.HTTP.Actions.LoadRequest');
		$this->setValue('Name','Modules.HTTP.Actions.LoadRequest');
		$this->setValue('Description', 'Loads the values from a HTTP Request');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
		
		$this->addAttribute('Request', array('Modules.HTTP.Object.Request'), true, false, true);
		
		$this->addEvent('RequestLoaded');
		$this->addEvent('RequestNotLoaded');
	}
	
	public function run(&$inputObject){
		
		if(!parent::beforeRun($inputObject)){
			return false;
		}
		
		$request = $this->getValue('Request');
		
		if(!$request){
			$this->fireEvent('RequestNotLoaded');	
		}else{
			
			$requestPath = explode('?', $_SERVER['REQUEST_URI']);
			$request->setValue('Domain', $_SERVER['HTTP_HOST']);
			$request->setValue('Path', $requestPath[0]);
			$request->setValue('POST', $_POST);
			$request->setValue('GET', $_GET);
			$request->setValue('FILES', $_FILES);
			$request->setValue('REFERER', $_SERVER['HTTP_REFERER']);
			$request->setValue('REMOTE_ADDRESS', $_SERVER['REMOTE_ADDR']);
			$request->setValue('USER_AGENT', $_SERVER['HTTP_USER_AGENT']);
			$this->setValue('Request', $request);
			if($inputObject->usesDefinition('Modules.HTTP.Object.Request')){
				$inputObject->setValue('Domain', $_SERVER['HTTP_HOST']);
				$inputObject->setValue('Path', $requestPath[0]);
				$inputObject->setValue('POST', $_POST);
				$inputObject->setValue('GET', $_GET);
				$inputObject->setValue('FILES', $_FILES);
				$inputObject->setValue('REFERER', $_SERVER['HTTP_REFERER']);
				$inputObject->setValue('REMOTE_ADDRESS', $_SERVER['REMOTE_ADDR']);
				$inputObject->setValue('USER_AGENT', $_SERVER['HTTP_USER_AGENT']);
			}
			$this->fireEvent('RequestLoaded');	
		}
		
		return parent::afterRun($inputObject);
	}
}
?>