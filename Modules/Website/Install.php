<?php
	//Create the Security Object Definitions
	class ModulesWebsiteInstall extends KernelObject{
		public function __construct($cfg=null){
			parent::__construct($cfg);
			
			$this->useDefinition('Object.Action');
			
			$this->setValue('ID','Modules.Website.Install');
			$this->setValue('Name','Website Module Installer');
			$this->setValue('Description', 'Installs the Website Module');
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
			$moduleDefinition->setValue('ID', 'Modules.Website');
			$moduleDefinition->setValue('Name', 'Website Module');
			$moduleDefinition->setValue('Author', 'Justin Pradier');
			$moduleDefinition->setValue('Version', '1.0.0');
			
			//Load the actions first because we'll need to use them for the data definitions
			
			//Action Definitions
				
			//End Action Definitions
			
			//Data Definitions
				//Domain Object Type
					$domainDefinition = new KernelObject();
					$domainDefinition->useDefinition('Object.Definition');
					$domainDefinition->setValue('ID','Modules.Website.Object.Domain');
					$domainDefinition->setValue('Name', 'Domain Object');
					$domainDefinition->setValue('Description', 'Domain Object Definition');
					$domainDefinition->setValue('Author', 'Justin Pradier');
					$domainDefinition->setValue('Version', '1.0.0');
					
					$domainDefinition->addAttribute('Domain', array('Object.String'), true, false);
					$domainDefinition->suspendEvents();
					$domainDefinition->save();
					
				//Website Object Type
					$websiteDefinition = new KernelObject();
					$websiteDefinition->useDefinition('Object.Definition');
					$websiteDefinition->setValue('ID','Modules.Website.Object.Website');
					$websiteDefinition->setValue('Name', 'Website Object');
					$websiteDefinition->setValue('Description', 'Website Object Definition');
					$websiteDefinition->setValue('Author', 'Justin Pradier');
					$websiteDefinition->setValue('Version', '1.0.0');
					
					$websiteDefinition->addAttribute('MetaTitle', array('Object.String'), true);
					$websiteDefinition->addAttribute('Domains', array('Modules.Website.Object.Domain'), false, true);
					
					$websiteDefinition->suspendEvents();
					$websiteDefinition->save();
				//ContentBlock Object Type
					$headerDefinition = new KernelObject();
					$headerDefinition->useDefinition('Object.Definition');
					$headerDefinition->setValue('ID','Modules.Website.Object.ContentBlock');
					$headerDefinition->setValue('Name', 'Header Object');
					$headerDefinition->setValue('Description', 'Website Page Header Definition');
					$headerDefinition->setValue('Author', 'Justin Pradier');
					$headerDefinition->setValue('Version', '1.0.0');
					
					$headerDefinition->addAttribute('Content', array('Object.String'), true);
					
					$headerDefinition->suspendEvents();
					$headerDefinition->save();
					
				//Page Object Type
					$pageDefinition = new KernelObject();
					$pageDefinition->useDefinition('Object.Definition');
					$pageDefinition->setValue('ID','Modules.Website.Object.Page');
					$pageDefinition->setValue('Name', 'Website Page Object');
					$pageDefinition->setValue('Description', 'Website Page Object Definition');
					$pageDefinition->setValue('Author', 'Justin Pradier');
					$pageDefinition->setValue('Version', '1.0.0');
					
					$pageDefinition->addAttribute('Path', array('Object.String'), true);
					$pageDefinition->addAttribute('Site', array('Modules.Website.Object.Website'), true);
					$pageDefinition->addAttribute('ContentBlocks', array('Modules.Website.Object.ContentBlock'));
					
					$pageDefinition->suspendEvents();
					$pageDefinition->save();
					
			$moduleDefinition->addValue('Definitions', $domainDefinition->getValue('ID'));
			$moduleDefinition->addValue('Definitions', $websiteDefinition->getValue('ID'));
			//End Data Definitions
			
			//Save the module
			$moduleDefinition->suspendEvents();
			$moduleDefinition->save();
		}
	}
?>
