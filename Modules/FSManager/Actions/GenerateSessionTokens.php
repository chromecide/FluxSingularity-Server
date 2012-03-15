<?php
class ModulesFSManagerActionsGenerateSessionTokens extends KernelObject{
	
	public function __construct($cfg=null){
		parent::__construct($cfg);
		$this->setValue('ID', 'Modules.FSManager.Actions.GenerateSessionokens');
		$this->setValue('Name', 'Modules.FSManager.Actions.GenerateSessionTokens');
		$this->setValue('Description', 'Generate Tokens used by a client session for signing messages');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
		
		$this->addAttribute('Count', 'Object.Number', true, false, true);
		$this->setValue('Count', 10);
	}
	
	public function run(&$inputObject){
		if(!parent::beforeRun($inputObject)){return false;}
		
		$inputObject->addTrace('Modules.FSManager.Actions.GenerateSessionTokens', 'Starting Action');
		
		//give the session access to the guest viewport
		if($inputObject->usesDefinition('API.Session')){
			$count = $this->getValue('Count');
			$tokens = array();
			if(is_numeric($count)){
				for($i=0; $i<$count; $i++){
					$tokens = md5(uniqid());
				}
			}
			$inputObject->setValue('Tokens', $tokens);
		
		}else{
			return false;
		}
		
		return parent::afterRun();
	}
	
	
}
?>