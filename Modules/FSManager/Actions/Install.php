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
		$this->installActionDefinitions();
		$this->installData();
	}
	
	public function installDefinitions(){
		//Create the Module Definition
		$moduleObject = new KernelObject();
		$moduleObject->useDefinition('Object.Module');
		$moduleObject->setValue('ID', 'Modules.FSManager');
		$moduleObject->setValue('Name', 'FSManager Module');
		$moduleObject->setValue('Description', 'FSManager Module');
		$moduleObject->setValue('Version', '1.0.0');
		$moduleObject->setValue('Author', 'Justin Pradier');
		
		//Toolbar
		$toolbarDefinition = new KernelObject('Object.Definition');
		$toolbarDefinition->setValue('ID', 'Modules.FSManager.Object.Toolbar');
		$toolbarDefinition->setValue('Name', 'Modules.FSManager.Object.Toolbar');
		$toolbarDefinition->setValue('Description', 'FSManager Toolbar Definition');
		$toolbarDefinition->setValue('Author', 'FSManager Module Installer');
		$toolbarDefinition->setValue('Version', '1.0.0');
		$toolbarDefinition->addAttribute('Items', 'Object', false, true, true);
		$toolbarDefinition->addAttribute('Enabled', 'Object.Boolean');
		$toolbarDefinition->addAttribute('Height', 'Object.Number');
		$toolbarDefinition->addAttribute('Title', 'Object.String');
		$objectList[] = $toolbarDefinition;
		
		//Toolbar Button
		$toolbarButtonDefinition = new KernelObject('Object.Definition');
		$toolbarButtonDefinition->setValue('ID', 'Modules.FSManager.Object.Toolbar.Button');
		$toolbarButtonDefinition->setValue('Name', 'Modules.FSManager.Object.Toolbar.Button');
		$toolbarButtonDefinition->setValue('Description', 'FSManager Toolbar Buttonm Definition');
		$toolbarButtonDefinition->setValue('Author', 'FSManager Module Installer');
		$toolbarButtonDefinition->setValue('Version', '1.0.0');
		$toolbarButtonDefinition->addAttribute('Text', 'Object.String');
		$toolbarButtonDefinition->addAttribute('Icon', 'Object.String');
		$toolbarButtonDefinition->addAttribute('AppName', 'Object.String');
		$toolbarButtonDefinition->addAttribute('AppCfg', 'Object');
		$objectList[] = $toolbarButtonDefinition;
		
		//Sidbar
		$sidebarDefinition = new KernelObject('Object.Definition');
		$sidebarDefinition->setValue('ID', 'Modules.FSManager.Object.Sidebar');
		$sidebarDefinition->setValue('Name', 'Modules.FSManager.Object.Sidebar');
		$sidebarDefinition->setValue('Description', 'FSManager Sidebar Definition');
		$sidebarDefinition->setValue('Author', 'FSManager Module Installer');
		$sidebarDefinition->setValue('Version', '1.0.0');
		$sidebarDefinition->addAttribute('Items', 'Object', false, true);
		$sidebarDefinition->addAttribute('Enabled', 'Object.Boolean');
		$sidebarDefinition->addAttribute('Width', 'Object.Number');
		$objectList[] = $sidebarDefinition;
		
		//DashboardColumn Definition
		$dashboardColumnDefinition = new KernelObject('Object.Definition');
		$dashboardColumnDefinition->setValue('ID', 'Modules.FSManager.Object.DashboardColumn');
		$dashboardColumnDefinition->setValue('Name', 'Modules.FSManager.Object.DashboardColumn');
		$dashboardColumnDefinition->setValue('Description', 'FSManager Dashboard Column Definition');
		$dashboardColumnDefinition->setValue('Author', 'FSManager Module Installer');
		$dashboardColumnDefinition->setValue('Version', '1.0.0');
		$dashboardColumnDefinition->addAttribute('DisplayOrder', 'Object.Number');
		$dashboardColumnDefinition->addAttribute('Items', 'Object', false, true);
		$objectList[] = $dashboardColumnDefinition;
		
		//Dashboard Definition
		$dashboardDefinition = new KernelObject('Object.Definition');
		$dashboardDefinition->setValue('ID', 'Modules.FSManager.Object.Dashboard');
		$dashboardDefinition->setValue('Name', 'Modules.FSManager.Object.Dashboard');
		$dashboardDefinition->setValue('Description', 'FSManager Dashboard Definition');
		$dashboardDefinition->setValue('Author', 'FSManager Module Installer');
		$dashboardDefinition->setValue('Version', '1.0.0');
		$dashboardDefinition->addAttribute('Columns', 'Modules.FSManager.Object.DashboardColumn', false, true);
		$objectList[] = $dashboardDefinition;
		
		//Viewport Definition
		$viewportDefinition = new KernelObject('Object.Definition');
		$viewportDefinition->setValue('ID', 'Modules.FSManager.Object.Viewport');
		$viewportDefinition->setValue('Name', 'Modules.FSManager.Object.Viewport');
		$viewportDefinition->setValue('Description', 'FSManager Viewport Definition');
		$viewportDefinition->setValue('Author', 'FSManager Module Installer');
		$viewportDefinition->setValue('Version', '1.0.0');	
		$viewportDefinition->addAttribute('TopBar', 'Modules.FSManager.Object.Toolbar', false, false, true);
		$viewportDefinition->addAttribute('BottomBar', 'Modules.FSManager.Object.Toolbar', false, false);
		$viewportDefinition->addAttribute('LeftBar', 'Modules.FSManager.Object.Sidebar', false, false);
		$viewportDefinition->addAttribute('RightBar', 'Modules.FSManager.Object.Sidebar', false, false);
		$viewportDefinition->addAttribute('Dashboards', 'Modules.FSManager.Object.Dashboard', false, true);
		$objectList[] = $viewportDefinition;
		
		//Application Definition
		$applicationDefinition = new KernelObject('Object.Definition');
		$applicationDefinition->setValue('ID', 'Modules.FSManager.Object.Application');
		$applicationDefinition->setValue('Name', 'Modules.FSManager.Object.Application');
		$applicationDefinition->setValue('Description', 'FSManager Application Definition');
		$applicationDefinition->setValue('Author', 'FSManager Module Installer');
		$applicationDefinition->setValue('Version', '1.0.0');
		$applicationDefinition->addAttribute('ClientApplicationName', '.Object.String');
		$objectList[] = $applicationDefinition;
		
		//Session Definition
		/*$sessionDefinition = new KernelObject('Object.Definition');
		$sessionDefinition->setValue('ID', 'Modules.FSManager.Object.Session');
		$sessionDefinition->setValue('Name', 'Modules.FSManager.Object.Session');
		$sessionDefinition->setValue('Description', 'FSManager Session Definition');
		$sessionDefinition->setValue('Author', 'FSManager Module Installer');
		$sessionDefinition->setValue('Version', '1.0.0');
		$sessionDefinition->addAttribute('SessionID', 'Object.String');
		$sessionDefinition->addAttribute('User', 'Object.Security.User', false, false, false);
		$sessionDefinition->addAttribute('LastAccessed', 'Object.Date', true);
		$sessionDefinition->addAttribute('LinkedSessions', 'Object', false, true, false);
		//$sessionDefinition->addEvent('NewSession');
		$sessionDefinition->addAction('NewSession', 'Generate Tokens', 'Modules.FSManager.Actions.GenerateSessionTokens');
		$objectList[] = $sessionDefinition;*/
		
		
		
		//Message Definition
		$messageDefinition = new KernelObject('Object.Definition');
		$messageDefinition->setValue('ID', 'Modules.FSManager.Object.Message');
		$messageDefinition->setValue('Name', 'Modules.FSManager.Object.Message');
		$messageDefinition->setValue('Description', 'FSManager Message Definition');
		$messageDefinition->setValue('Author', 'FSManager Module Installer');
		$messageDefinition->setValue('Version', '1.0.0');
		$messageDefinition->addAttribute('Source', 'Object.String', true, false);
		$messageDefinition->addAttribute('Destination', 'Object.String', true, false);
		$messageDefinition->addAttribute('MessageBody', 'Object', true, false);
		//$messageDefinition->addAction('BeforeSave', 'Modules.FSManager.Actions.UpdateSession');
		//$messageDefinition->addAction('BeforeSave', 'Kernel.Actions.Stop');//prevent saving an fsmanager message
		
		$objectList[] = $messageDefinition;
		
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
		//Assign Guest Viewport
		$assignGuestViewportAction = new ModulesFSManagerActionsAssignGuestViewport();
		$assignGuestViewportAction->save();
	}
	
	public function installData(){
		//Create the Admin User Account
		$adminAccount = new KernelObject('Object.Security.User');
		$adminAccount->setValue('Name', 'FSManager Administrator Account');
		$adminAccount->setValue('Description', 'FSManager Administrator Account Object');
		$adminAccount->setValue('Version', '1.0.0');
		$adminAccount->setValue('Author', 'FSManager Module Installer');
		$adminAccount->setValue('Username', 'administrator');
		$adminAccount->setValue('Password', 'adminpassword');
		$objectList[] = $adminAccount;
		
		//Create the GuestViewport
		$loginButton = new KernelObject('Modules.FSManager.Object.Toolbar.Button');
		$loginButton->setValue('Name', 'LoginButton');
		$loginButton->setValue('Description', 'LoginButton');
		$loginButton->setValue('Author', 'FSManager Module Installer');
		$loginButton->setValue('Text', 'Login');
		$loginButton->setValue('Icon', 'icon-lock');
		$loginButton->setValue('AppName', 'FSManager.applications.UserLogin');
		
		$guestToolbar = new KernelObject('Modules.FSManager.Object.Toolbar');
		$guestToolbar->setValue('Name', 'Guest Toolbar');
		$guestToolbar->setValue('Description', 'Guest Toolbar');
		$guestToolbar->setValue('Author', 'FSManager Module Installer');
		$guestToolbar->setValue('Version', '1.0.0');
		$guestToolbar->setValue('Enabled', true);
		$guestToolbar->setValue('Title', 'Flux Singularity');
		$guestToolbar->addValue('Items', $loginButton);
		
		$guestViewport = new KernelObject('Modules.FSManager.Object.Viewport');
		$guestViewport->setValue('Name', 'FSManager Guest Viewport');
		$guestViewport->setValue('Description', 'FSManager Guest Viewport Object');
		$guestViewport->setValue('Version', '1.0.0');
		$guestViewport->setValue('Author', 'FSManager Module Installer');
		$guestViewport->setValue('TopBar', $guestToolbar);
		fb($guestViewport->toArray());
		$objectList[] = $guestViewport;
		$continue = true;
		foreach($objectList as &$object){
			if($continue){
				if(!$object->save()){
					echo 'could not install'.$object->getValue('Name').'<br/>';
					fb($object->getValue('TopBar'));
					fb($object);
					$continue = false;
				}	
			}
		}
	}
}
?>