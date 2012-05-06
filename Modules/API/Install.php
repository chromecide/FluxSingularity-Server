<?php
include_once('AssignGuestViewport.php');
include_once('AuthenticateUser.php');
include_once('UpdateSession.php');

class ModulesFSManagerActionsInstall extends KernelObject{
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->useDefinition('Object.Action');
		
		$this->setValue('ID','Modules.FSManager.Actions.Install');
		$this->setValue('Name','FSManager Installer');
		$this->setValue('Description', 'Installs the FSManager Module');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
	}
	
	public function run(){
		$this->installDefinitions();
		//$this->installActionDefinitions();
		//$this->installData();
	}
	
	public function installDefinitions(){
		$objectList = array();
		//Modules.API.Token
			$tokenDefinition = new KernelObject();
			$tokenDefinition->useDefinition('Object');
			$tokenDefinition->setValue('ID', 'Modules.API.Token');
			$tokenDefinition->setValue('Name', 'Modules.API.Token');
			$tokenDefinition->setValue('Description', 'API Token Definition');
			$tokenDefinition->setValue('Author', 'Justin Pradier');
			$tokenDefinition->setValue('Version', '1.0.0');
			
			$tokenDefinition->addAttribute('Mode', array('Object.String'), true);
			$tokenDefinition->addAttribute('AllowedDefinitions', array('Object'), false, true);
		
		$objectList[] = $tokenDefinition;
		
		//Modules.API.Client
			$clientDefinition = new KernelObject();
			$clientDefinition->useDefinition('Object');
			$clientDefinition->setValue('ID', 'Modules.API.Client');
			$clientDefinition->setValue('Name', 'Modules.API.Client');
			$clientDefinition->setValue('Description', 'API Client Definition');
			$clientDefinition->setValue('Author', 'Justin Pradier');
			$clientDefinition->setValue('Version', '1.0.0');
			
			$clientDefinition->addAttribute('ClientKey', array('Object.String'), true);
			
		$objectList[] = $clientDefinition;
		
		//Modules.API.Session
			$sessionDefinition = new KernelObject();
			$sessionDefinition->useDefinition('Object');
			$sessionDefinition->setValue('ID', 'Modules.API.Client');
			$sessionDefinition->setValue('Name', 'Modules.API.Client');
			$sessionDefinition->setValue('Description', 'API Client Definition');
			$sessionDefinition->setValue('Author', 'Justin Pradier');
			$sessionDefinition->setValue('Version', '1.0.0');
			
			$sessionDefinition->addAttribute('User', array('Object.Security.User'), true);
			$sessionDefinition->addAttribute('LastAccessed', array('Object.Date'), true);
			$sessionDefinition->addAttribute('LinkedSessions', array('Modules.API.Session'), false, true, false);
			$sessionDefinition->addAttribute('Tokens', array('Modules.API.Token'), true, true, false);
			$sessionDefinition->addAttribute('RemoteAddress', array('Object.String'), true, false, true);
			$sessionDefinition->addAttribute('Client', array('Modules.API.Client'), true, false, true);
			
		$objectList[] = $sessionDefinition;
			
			$readDefinition = new KernelObject();
			$readDefinition->useDefinition('Object');
			$readDefinition->setValue('ID', 'Modules.API.Read');
			$readDefinition->setValue('Name', 'Modules.API.Read');
			$readDefinition->setValue('Description', 'API Read Object');
			$readDefinition->setValue('Author', 'Justin Pradier');
			$readDefinition->setValue('Version', '1.0.0');
			
			$readDefinition->addAttribute('Session', array('Modules.API.Session'), true, false, false);
			$readDefinition->addAttribute('Client', array('Modules.API.Client'), true, false, false);
			$readDefinition->addAttribute('Token', array('Modules.API.Client'), true, false, false);
			$readDefinition->addAttribute('Queries', array('Object.Query'), true, true, true);
		
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