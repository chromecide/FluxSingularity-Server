<?php
	class ModulesSecurityActionsAddSecurityFields extends KernelActionsAction{
		public function __construct(){
			
		}
		
		public function run(&$object){
			$object->addField('Owner', 'Modules.Security.Object.User', true, true);
			$object->addField('Permissions', 'Modules.Security.Object.Permission', true, true);
			
			return true;
		}
	}
?>