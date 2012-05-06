<?php
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
		$this->installActionDefinitions();
		$this->installData();
	}
	
	public function installDefinitions(){
		$objectList = array();
		
		//Modules.Process.Process
			$processDefinition = new KernelObject('Object.Definition');
			$processDefinition->setValue('ID', 'Modules.Processes.Process');
			$processDefinition->setValue('Name', 'Modules.Processes.Process');
			$processDefinition->setValue('Description', 'Process Definition');
			$processDefinition->setValue('Author', 'Justin Pradier');
			$processDefinition->setValue('Version', '1.0.0');
			
			$processDefinition->addAttribute('ActionList', 'Object.Action', true, true);
			$processDefinition->addAttribute('ObjectMap', 'Object.AttributeMap', true, true);
			
		$objectList[] = $processDefinition;
		
		
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