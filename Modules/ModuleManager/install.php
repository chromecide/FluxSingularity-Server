<?php

	//Create the Security Object Definitions
	class ModulesModuleManagerInstall{
		public static function installModule(){
			$installed = false;
			if(self::registerDefinitions()){
				if(self::registerEvents()){
					$installed = true;
				}	
			}
			
			return $installed;
		}
		
		public static function registerDefinitions(){
			$moduleDataDefinition = new KernelObject();
			$moduleDataDefinition->setObjectName('Modules.ModuleManager.Object.Module');
			$moduleDataDefinition->setObjectDescription('Module Definition');
			$moduleDataDefinition->setObjectAuthor('Justin Pradier');
			$moduleDataDefinition->setObjectVersion('1.0.0');
			$moduleDataDefinition->addField('DefinitionList', 'Kernel.Object.String', false, true, 'Module Definitions List');
			$moduleDataDefinition->addField('ActionList', 'Kernel.Object.String', false, true, 'Module Actions List');
			
			if(!$moduleDataDefinition->save()){
				return false;
			}
			
			$moduleDefinition = new KernelObject('Modules.ModuleManager.Object.Module');
			
			$moduleDefinition->setObjectName('Module Manager');
			$moduleDefinition->setObjectDescription('Module Management Module');
			$moduleDefinition->setObjectAuthor('Justin Pradier');
			$moduleDefinition->setObjectVersion('1.0.0');
			$moduleDefinition->setValue('DefinitionList', array('Modules.ModuleManager.Object.Module'));
			$moduleDefinition->setValue('ActionList', array('Modules.ModuleManager.Action.InstallModule'));
			
			if(!$moduleDefinition->save()){
				$moduleDataDefinition->remove();
				return false;
			}
			return true;
		}

		public function registerEvents(){
			return true;
		}
	}
?>
