<?php
	//Create the Security Object Definitions
	class ModulesHTTPInstall extends KernelObject{
		public function __construct($cfg=null){
			parent::__construct($cfg);
			
			$this->useDefinition('Object.Action');
			
			$this->setValue('ID','Modules.HTTP.Install');
			$this->setValue('Name','HTTP Module Installer');
			$this->setValue('Description', 'Installs the HTTP Module');
			$this->setValue('Author', 'Justin Pradier');
			$this->setValue('Version', '1.0.0');
		}
		
		public function run(){
			$this->installDefinitions();
			//$this->installData();
		}
		
		public static function installDefinitions(){
			$moduleDefinition = new KernelObject();
			$moduleDefinition->useDefinition('Object.Module');
			$moduleDefinition->setValue('ID', 'Modules.HTTP');
			$moduleDefinition->setValue('Name', 'HTTP Module');
			$moduleDefinition->setValue('Author', 'Justin Pradier');
			$moduleDefinition->setValue('Version', '1.0.0');
			
			//Load the actions first because we'll need to use them for the data definitions
			
			//Action Definitions
				//Load Request Object
				$loadRequestDefinition = new KernelObject();
				$loadRequestDefinition->loadById('Modules.HTTP.Actions.LoadRequest');
				$loadRequestDefinition->suspendEvents();
				$loadRequestDefinition->save();
				
				//Send Response Object
				$sendResponseDefinition = new KernelObject();
				$sendResponseDefinition->loadById('Modules.HTTP.Actions.SendResponse');
				$sendResponseDefinition->suspendEvents();
				$sendResponseDefinition->save();
				
			$moduleDefinition->addValue('Actions', $loadRequestDefinition->getValue('ID'));
			$moduleDefinition->addValue('Actions', $sendResponseDefinition->getValue('ID'));
			//End Action Definitions
			
			//Data Definitions
				//Request Object Type
					$requestDefinition = new KernelObject();
					$requestDefinition->useDefinition('Object.Definition');
					$requestDefinition->setValue('ID', 'Modules.HTTP.Object.Request');
					$requestDefinition->setValue('Name', 'HTTP Request Object');
					$requestDefinition->setValue('Description', 'HTTP Request Object Definition');
					$requestDefinition->setValue('Author', 'Justin Pradier');
					$requestDefinition->setValue('Version', '1.0.0');
					
					$requestDefinition->addAttribute('Domain', array('Object.String'), true);
					$requestDefinition->addAttribute('Path', array('Object.String'), true);
					$requestDefinition->addAttribute('POST', array('Object.String'), false, true);
					$requestDefinition->addAttribute('GET', array('Object.String'), false, true);
					$requestDefinition->addAttribute('FILES', array('Object.String'), false, true);
					$requestDefinition->addAttribute('REFERER', array('Object.String'), false);
					$requestDefinition->addAttribute('REMOTE_ADDRESS', array('Object.String'), true);
					$requestDefinition->addAttribute('USER_AGENT', array('Object.String'), false);
					
					$requestDefinition->addSubscriber('AfterUseDefinitionInstance', $loadRequestDefinition, array(
						array(
							'Source'=>'InputObject',
							'Targets'=>array(
								'Request'
							)
						)
					));
					
					$requestDefinition->suspendEvents();
					$requestDefinition->save();
			
				//Response Object Type
					$responseDefinition = new KernelObject();
					$responseDefinition->useDefinition('Object.Definition');
					$responseDefinition->setValue('ID','Modules.HTTP.Object.Response');
					$responseDefinition->setValue('Name', 'HTTP Response Object');
					$responseDefinition->setValue('Description', 'HTTP Response Object Definition');
					$responseDefinition->setValue('Author', 'Justin Pradier');
					$responseDefinition->setValue('Version', '1.0.0');
					
					$responseDefinition->addAttribute('ContentType', array('Object.String'), true);
					$responseDefinition->addAttribute('Content', array('Object.String'), false);
					$responseDefinition->suspendEvents();
					$responseDefinition->save();
			
			$moduleDefinition->addValue('Definitions', $requestDefinition->getValue('ID'));
			$moduleDefinition->addValue('Definitions', $responseDefinition->getValue('ID'));
			//End Data Definitions
			
			//Save the module
			$moduleDefinition->suspendEvents();
			$moduleDefinition->save();
		}
	}
?>
