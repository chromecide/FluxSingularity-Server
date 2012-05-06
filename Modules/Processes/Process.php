<?php
class ModulesProcessesProcess extends KernelObject{
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->useDefinition('Object.Definition');
		
		$this->setValue('ID','Modules.FSManager.Actions.Install');
		$this->setValue('Name','FSManager Installer');
		$this->setValue('Description', 'Installs the FSManager Module');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
		
		$this->addAttribute('ActionList', 'Object.Action', true, true, false);
		$this->addAttribute('ObjectMapping', 'Object.AttributeMap', true, true);
	}
	
	public function run(){
		
	}
	
	public function installDefinitions(){
		$objectList = array();
		//Modules.API.Token
			$tokenDefinition = new KernelObject('Object.Definition');
			$tokenDefinition->setValue('ID', 'Modules.API.Token');
			$tokenDefinition->setValue('Name', 'Modules.API.Token');
			$tokenDefinition->setValue('Description', 'API Token Definition');
			$tokenDefinition->setValue('Author', 'Justin Pradier');
			$tokenDefinition->setValue('Version', '1.0.0');
			
			$tokenDefinition->addAttribute('Mode', 'Object.String', true);
			$tokenDefinition->addAttribute('AllowedDefinitions', 'Object.Definition', false, true);
		
		$objectList[] = $tokenDefinition;
		
		//Modules.API.Client
			$clientDefinition = new KernelObject('Object.Definition');
			$clientDefinition->setValue('ID', 'Modules.API.Client');
			$clientDefinition->setValue('Name', 'Modules.API.Client');
			$clientDefinition->setValue('Description', 'API Client Definition');
			$clientDefinition->setValue('Author', 'Justin Pradier');
			$clientDefinition->setValue('Version', '1.0.0');
			
			$clientDefinition->addAttribute('ClientKey', 'Object.String', true);
			
		$objectList[] = $clientDefinition;
		
		//Modules.API.Session
			$sessionDefinition = new KernelObject('Object.Definition');
			$sessionDefinition->setValue('ID', 'Modules.API.Client');
			$sessionDefinition->setValue('Name', 'Modules.API.Client');
			$sessionDefinition->setValue('Description', 'API Client Definition');
			$sessionDefinition->setValue('Author', 'Justin Pradier');
			$sessionDefinition->setValue('Version', '1.0.0');
			
			$sessionDefinition->addAttribute('User', 'Object.Security.User', true);
			$sessionDefinition->addAttribute('LastAccessed', 'Object.Date', true);
			$sessionDefinition->addAttribute('LinkedSessions', 'Modules.API.Session', false, true, false);
			$sessionDefinition->addAttribute('Tokens', 'Modules.API.Token', true, true, false);
			$sessionDefinition->addAttribute('RemoteAddress', 'Object.String', true, false, true);
			$sessionDefinition->addAttribute('Client', 'Modules.API.Client', true, false, true);
			
		$objectList[] = $sessionDefinition;
			
			$readDefinition = new KernelObject();
			$readDefinition->setValue('ID', 'Modules.API.Read');
			$readDefinition->setValue('Name', 'Modules.API.Read');
			$readDefinition->setValue('Description', 'API Read Object');
			$readDefinition->setValue('Author', 'Justin Pradier');
			$readDefinition->setValue('Version', '1.0.0');
			
			$readDefinition->addAttribute('Session', 'Modules.API.Session', true, false, false, 'Session');
			$readDefinition->addAttribute('Client', 'Modules.API.Client', true, false, false, 'API Client');
			$readDefinition->addAttribute('Token', 'Modules.API.Client', true, false, false, 'API Client');
			$readDefinition->addAttribute('Queries', 'Object.Query', true, true, true, 'Queries');
		
		$objectList[] = $readDefinition;
		
		$continue = true;
		$rollback = false;
		
		foreach($objectList as &$object){
			if($continue){
				$object->suspendEvents();
				if(!$object->save()){
					$rollback = true;
					$continue = false;
				}
				$object->resumeEvents();
			}
		}
		
		return !$rollback;
	}

	public function installActionDefinitions(){
		$objectList = array();
		
		$continue = true;
		$rollback = false;
		
		foreach($objectList as &$object){
			if($continue){
				$object->suspendEvents();
				if(!$object->save()){
					$rollback = true;
					$continue = false;
				}
				$object->resumeEvents();
			}
		}
		
		return !$rollback;
	}
	
	public function installData(){
		
	}
}
?>