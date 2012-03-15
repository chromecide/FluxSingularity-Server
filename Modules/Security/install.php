<?php

	//Create the Security Object Definitions
	class ModulesSecurityInstall{
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
			
			//User
			$userDefinition = new KernelObject();
			$userDefinition->setObjectName('Modules.Security.Object.User');
			$userDefinition->setObjectDescription('Security User');
			$userDefinition->setObjectAuthor('Justin Pradier');
			$userDefinition->setObjectVersion('1.0.0');
			$userDefinition->addField('Username', 'Kernel.Object.String', true, false);
			$userDefinition->addField('Password', 'Kernel.Object.String', true, false);
			$userDefinition->addField('DisplayName', 'Kernel.Object.String', true, false, 'Display Name');
			$userDefinition->addField('Networks', 'Modules.Security.Object.Network', true, true, 'Networks');
			
			if(!$userDefinition->save()){
				return false;
			}
			
			//Network
			$networkDefinition = new KernelObject();
			$networkDefinition->setObjectName('Modules.Security.Object.Network');
			$networkDefinition->setObjectDescription('Security Network');
			$networkDefinition->setObjectAuthor('Justin Pradier');
			$networkDefinition->setObjectVersion('1.0.0');
			$networkDefinition->addField('Users', 'Kernel.Object.String', true, true);
			$networkDefinition->addField('Networks', 'Modules.Security.Object.Network', true, true, 'Networks');
			
			if(!$networkDefinition->save()){
				$userDefinition->remove();
				return false;
			}
			
			//Permission
			$permissionDefinition = new KernelObject();
			$permissionDefinition->setObjectName('Modules.Security.Object.Permission');
			$permissionDefinition->setObjectDescription('Security Network');
			$permissionDefinition->setObjectAuthor('Justin Pradier');
			$permissionDefinition->setObjectVersion('1.0.0');
			$permissionDefinition->addField('Read', 'Kernel.Object.Boolean', false, false);
			$permissionDefinition->addField('Modify', 'Kernel.Object.Boolean', false, false);
			$permissionDefinition->addField('Remove', 'Kernel.Object.Boolean', false, false);
			$permissionDefinition->addField('Networks', 'Modules.Security.Object.Network', true, true);
			$permissionDefinition->addField('Users', 'Modules.Security.Object.User', true, true);
			
			if(!$permissionDefinition->save()){
				$userDefinition->remove();
				$networkDefinition->remove();
				return false;
			}
			
			
			//Authenticated Session
			$authSessionDefinition = new KernelObject();
			$authSessionDefinition->setObjectName('Modules.Security.Object.AuthenticatedSession');
			$authSessionDefinition->setObjectDescription('Session using the Security Layer');
			$authSessionDefinition->setObjectAuthor('Justin Pradier');
			$authSessionDefinition->setObjectVersion('1.0.0');
			$authSessionDefinition->addField('Owner', 'Modules.Security.Object.User', true, false);
			
			return true;
		}

		public function createObjects(){
			$moduleDefinition = new KernelObject('Modules.ModuleManager.Object.Module');
			$moduleDefinition->setObjectName('Security Manager');
			$moduleDefinition->setObjectDescription('Security Management Module');
			$moduleDefinition->setObjectAuthor('Justin Pradier');
			$moduleDefinition->setObjectVersion('1.0.0');
			$moduleDefinition->setValue('DefinitionList', array(
				'Modules.Security.Object.User',
				'Modules.Security.Object.Network',
				'Modules.Security.Object.Permission'
			));
			$moduleDefinition->setValue('ActionList', array(
				'Modules.Security.Action.AddSecurityFields',
				'Modules.Security.Action.SetObjectOwner',
				'Modules.Security.Action.StartSecureSession',
				'Modules.Security.Action.EndSecureSession',
			));
			
			if(!$moduleDefinition->save()){
				return false;
			}
			
			//Kernel User Object
			$kernelUser = new KernelObject('Modules.Security.Object.User');
			$kernelUser->setValue('Username', 'TheKernel');
			$kernelUser->setValue('Username', 'aedyn1');
			if(!$kernelUser->save()){
				$moduleDefinition->remove();
				return false;
			}
		}

		public function registerEvents(){
			//KernelObject, BeforeCreate, add security fields
			$SecurityFieldsAction = new KernelObject();
			$SecurityFieldsAction->addDefinition('Kernel.Event');
			
			$SecurityFieldsAction->setObjectName('Kernel.Object Add Security Fields');
			$SecurityFieldsAction->setObjectDescription('Adds the Security Fields to all new Kernel.Objects');
			$SecurityFieldsAction->setObjectAuthor('Justin Pradier');
			$SecurityFieldsAction->setObjectVersion('1.0.0');
			
			$SecurityFieldsAction->setValue('Event', 'AfterCreate');
			$SecurityFieldsAction->setValue('TargetObjectDefinition', 'Kernel.Object');
			
			$actions = array(
				'Modules.Security.Actions.AddSecurityFields'
			);
			
			$SecurityFieldsAction->setValue('Actions', $actions);
			
			if(!$SecurityFieldsAction->save()){
				print_r($SecurityFieldsAction->getObjectErrors());
				return false;
			}
			return true;
		}
	}
?>
