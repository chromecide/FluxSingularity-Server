<?php
class ModulesFSManagerActionsAssignGuestViewport extends KernelObject{
	
	public function __construct($cfg=null){
		parent::__construct($cfg);
		$this->setValue('ID', 'Modules.FSManager.Actions.AssignGuestViewport');
		$this->setValue('Name', 'Modules.FSManager.Actions.AssignGuestViewport');
		$this->setValue('Description', 'Attaches the default viewport to the supplied session');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
	}
	
	public function run(&$inputObject){
		
		$inputObject->addTrace('Modules.FSManager.Actions.AssignGuestViewport', 'Starting Action');
		
		//give the session access to the guest viewport
		if($inputObject->usesDefinition('Modules.FSManager.Object.Session')){
			$permission = new KernelObject('Object.Permission');
			$permission->setValue('Recipient', $inputObject);
			$permission->setValue('Read', true);
			
			//load the guest viewport
			$viewport = new KernelObject('Modules.FSManager.Object.Viewport');
			$viewport->setValue('Name', 'FSManager Guest Viewport');
			
			if($viewport->findOne()){
				//echo 'adding viewport permission<br/>';
				$viewport->addPermission($inputObject, true, false, false, false); //read only access to the guest viewport
				//print_r($viewport->toArray());
				$viewport->save();
			}
		}
		
		return true;
	}
	
	
}
?>