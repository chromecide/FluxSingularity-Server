<?php
	//Create the Security Object Definitions
	class ModulesHTTPInstall{
		public static function installModule(){
			self::buildDefinitions();		
			
		}
		
		public static function buildDefinitions(){
			$moduleDefinition = new KernelObject();
			$moduleDefinition->setObjectName('Modules.HTTP');
			$moduleDefinition->setObjectDescription('HTTP Module');
			$moduleDefinition->setObjectAuthor('Justin Pradier');
			$moduleDefinition->setObjectVersion('1.0.0');
			$moduleDefinition->setValue('ModuleDefinitions', '');
			$moduleDefinition->setValue('ModuleActions', '');
			
			$moduleDefinition->save();
			
			//Request Object Type
			$requestDefinition = new KernelObject();
			$requestDefinition->setObjectName('Modules.HTTP.Request');
			$requestDefinition->setObjectDescription('HTTP Request Object');
			$requestDefinition->setObjectAuthor('Justin Pradier');
			$requestDefinition->setObjectVersion('1.0.0');
			
			$requestDefinition->addField('Domain', 'Kernel.Object.String', true);
			$requestDefinition->addField('Path', 'Kernel.Object.String', true);
			$requestDefinition->addField('POST', 'Kernel.Object.String', false, true);
			$requestDefinition->addField('GET', 'Kernel.Object.String', false, true);
			$requestDefinition->addField('FILES', 'Kernel.Object.String', false, true);
			$requestDefinition->addField('REFERER', 'Kernel.Object.String', false);
			$requestDefinition->addField('REMOTE_ADDRESS', 'Kernel.Object.String', true);
			$requestDefinition->addField('USER_AGENT', 'Kernel.Object.String', false);
			
			$requestDefinition->addAction('AfterCreate', 'Modules.HTTP.LoadRequestObject');
			
			$requestDefinitionSaved = $requestDefinition->save();
			if(!$requestDefinitionSaved){
				die('Request Object not Installed');
			}
			
			//Response Object Type
			$responseDefinition = new KernelObject();
			$responseDefinition->setObjectName('Modules.HTTP.Response');
			$responseDefinition->setObjectDescription('HTTP Response Object');
			$responseDefinition->setObjectAuthor('Justin Pradier');
			$responseDefinition->setObjectVersion('1.0.0');
			
			$responseDefinition->addField('ContentType', 'Kernel.Object.String', true);
			$responseDefinition->addField('Content', 'Kernel.Object.String', false, true);
			
			$responseDefinitionSaved = $responseDefinition->save();
		}
		
		public function registerActions(){
			
		}
	}
?>
