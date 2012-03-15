<?php
session_start();

include_once('config.php');

$createdObjects = array();

echo '<table border="1"><tr><td>Object Name</td></td><td>Status</td></tr>';

//create the admin user
$adminUser = $FSKernel->createObject('Kernel.Object.User');
$adminUser->setValue('Name', 'Administrator');
$adminUser->setValue('Name', 'Administrator User Object');
$adminUser->setValue('Name', 'Administrator');
$adminUser->setValue('Name', 'Administrator');

$adminUser->setObjectDescription('Administrator User Object');
$adminUser->setObjectAuthor('Flux Singularity Installer');
$adminUser->setObjectVersion('1.0.0');
$adminUser->setValue('Username', 'Administrator');
$adminUser->setValue('Password', 'AdminPassword');
$adminUser->setValue('DisplayName', 'System Administrator');
$adminUser->addPermission($kernelNetworkPermission);
$adminUser->addPermission($kernelUserPermission);

$objectList[] = $adminUser;

//create the admin user network
$adminNetwork = $FSKernel->createObject('Kernel.Object.Network');
$adminNetwork->setObjectName('Admin User Network');
$adminNetwork->setObjectDescription('Admin User Network');
$adminNetwork->setObjectAuthor('Flux Singularity Installer');
$adminNetwork->setObjectVersion('1.0.0');
$adminNetwork->addValue('Users', $adminUser);
$adminNetwork->addPermission($kernelNetworkPermission);
$adminNetwork->addPermission($kernelUserPermission);


$objectList[] = $adminNetwork;

//create the module entries
/*$securityModule = $FSKernel->createObject('Kernel.Module');
$securityModule->setObjectName('Security Module');
$securityModule->setObjectDescription('Security Management Module');
$securityModule->setObjectAuthor('Flux Singularity Installer');
$securityModule->setObjectVersion('1.0.0');

$securityActionList = array(
	'Modules.Security.Actions.AuthenticateUser',
	'Modules.Security.Actions.StartSecureSession',
	'Modules.Security.Actions.EndSecureSession',
	'Modules.Security.Actions.SetObjectOwner',
);

$securityDefinitionList = array(
	'Kernel.Object.User',
	'Kernel.Object.Network',
	'Kernel.Object.Permission'
);

$securityModule->setValue('ActionList', $securityActionList);
$securityModule->setValue('DefinitionList', $securityDefinitionList);
$objectList[] = $securityModule;
 * */



//Toolbar Definition
	$toolbarDefinition = $FSKernel->createObject('Kernel.Definition');
	$toolbarDefinition->setObjectName('Modules.FSManager.Object.Toolbar');
	$toolbarDefinition->setObjectDescription('FSManager Toolbar Definition');
	$toolbarDefinition->setObjectAuthor('Flux Singularity Installer');
	$toolbarDefinition->setObjectVersion('1.0.0');
	
	$toolbarDefinition->addField('Items', 'Kernel.Object', false, true, true);
	$toolbarDefinition->addField('Enabled', 'Kernel.Object.Boolean');
	$toolbarDefinition->addField('Height', 'Kernel.Object.Number');
	$toolbarDefinition->addField('Title', 'Kernel.Object.String');
	
	$objectList[] = $toolbarDefinition;
	
//Toolbar Button
	$toolbarButtonDefinition = $FSKernel->createObject('Kernel.Definition');
	$toolbarButtonDefinition->setObjectName('Modules.FSManager.Object.Toolbar.Button');
	$toolbarButtonDefinition->setObjectDescription('FSManager Toolbar Button Definition');
	$toolbarButtonDefinition->setObjectAuthor('Flux Singularity Installer');
	$toolbarButtonDefinition->setObjectVersion('1.0.0');
	
	$toolbarButtonDefinition->addField('Text', 'Kernel.Object.String');
	$toolbarButtonDefinition->addField('Icon', 'Kernel.Object.String');
	$toolbarButtonDefinition->addField('AppName', 'Kernel.Object.Number');
	$toolbarButtonDefinition->addField('AppCfg', 'Kernel.Object');
	
	$objectList[] = $toolbarButtonDefinition;

//Sidebar Definition
	$sidebarDefinition = $FSKernel->createObject('Kernel.Definition');
	$sidebarDefinition->setObjectName('Modules.FSManager.Object.Sidebar');
	$sidebarDefinition->setObjectDescription('FSManager Sidebar Definition');
	$sidebarDefinition->setObjectAuthor('Flux Singularity Installer');
	$sidebarDefinition->setObjectVersion('1.0.0');
	
	$sidebarDefinition->addField('Items', 'Kernel.Object', false, true);
	$sidebarDefinition->addField('Enabled', 'Kernel.Object.Boolean');
	$sidebarDefinition->addField('Width', 'Kernel.Object.Number');
	
	$objectList[] = $sidebarDefinition;
	
//DashboardColumn Definition
	$dashboardColumnDefinition = $FSKernel->createObject('Kernel.Definition');
	$dashboardColumnDefinition->setObjectName('Modules.FSManager.Object.DashboardColumn');
	$dashboardColumnDefinition->setObjectDescription('FSManager Dashboard ColumnDefinition');
	$dashboardColumnDefinition->setObjectAuthor('Flux Singularity Installer');
	$dashboardColumnDefinition->setObjectVersion('1.0.0');
	
	$dashboardColumnDefinition->addField('DisplayOrder', 'Kernel.Object.Number');
	$dashboardColumnDefinition->addField('Items', 'Kernel.Object', false, true);
	$objectList[] = $dashboardColumnDefinition;
	
//Dashboard Definition
	$dashboardDefinition = $FSKernel->createObject('Kernel.Definition');
	$dashboardDefinition->setObjectName('Modules.FSManager.Object.Dashboard');
	$dashboardDefinition->setObjectDescription('FSManager Dashboard Definition');
	$dashboardDefinition->setObjectAuthor('Flux Singularity Installer');
	$dashboardDefinition->setObjectVersion('1.0.0');
	
	$dashboardDefinition->addField('Columns', 'Modules.FSManager.Object.DashboardColumn', false, true);
	$objectList[] = $dashboardDefinition;
	
//Viewport Definition
	$viewportDefinition = $FSKernel->createObject('Kernel.Definition');
	$viewportDefinition->setObjectName('Modules.FSManager.Object.Viewport');
	$viewportDefinition->setObjectDescription('FSManager Viewport Definition');
	$viewportDefinition->setObjectAuthor('Flux Singularity Installer');
	$viewportDefinition->setObjectVersion('1.0.0');	
	$viewportDefinition->addField('TopBar', 'Modules.FSManager.Object.Toolbar', false, false, true);
	$viewportDefinition->addField('BottomBar', 'Modules.FSManager.Object.Toolbar', false, false);
	$viewportDefinition->addField('LeftBar', 'Modules.FSManager.Object.Sidebar', false, false);
	$viewportDefinition->addField('RightBar', 'Modules.FSManager.Object.Sidebar', false, false);
	$viewportDefinition->addField('Dashboards', 'Modules.FSManager.Object.Dashboard', false, true);

	$objectList[] = $viewportDefinition;
//Application Definition
	$applicationDefinition = $FSKernel->createObject('Kernel.Definition');
	$applicationDefinition->setObjectName('Modules.FSManager.Object.Application');
	$applicationDefinition->setObjectDescription('FSManager Application Definition');
	$applicationDefinition->setObjectAuthor('Flux Singularity Installer');
	$applicationDefinition->setObjectVersion('1.0.0');
	
	$applicationDefinition->addField('ClientApplicationName', 'Kernel.Object.String');
	
	$objectList[] = $applicationDefinition;
	
//Session Definition
	$sessionDefinition = $FSKernel->createObject('Kernel.Definition');
	
	$sessionDefinition->setObjectName('Modules.FSManager.Object.Session');
	$sessionDefinition->setObjectDescription('FSManager Session Definition');
	$sessionDefinition->setObjectAuthor('Flux Singularity Installer');
	$sessionDefinition->setObjectVersion('1.0.0');
	
	$sessionDefinition->addField('SessionID', 'Kernel.Object.String');
	$sessionDefinition->addField('User', 'Kernel.Object.User', false);
	$sessionDefinition->addField('LastAccessed', 'Kernel.Object.Date', true);
	$sessionDefinition->addField('LinkedSessions', 'Kernel.Object', false, true);
	
	$sessionDefinition->addEvent('NewSession');
	$sessionDefinition->addAction(
		'NewSession', 
		'Modules.FSManager.Actions.AssignGuestViewport'
	);
	$objectList[] = $sessionDefinition;
	
//Message Definition
	$messageDefinition = $FSKernel->createObject('Kernel.Definition');
	$messageDefinition->setObjectName('Modules.FSManager.Object.Message');
	$messageDefinition->setObjectDescription('FSManager Message Definition');
	$messageDefinition->setObjectAuthor('Flux Singularity Installer');
	$messageDefinition->setObjectVersion('1.0.0');
	
	$messageDefinition->addField('Source', 'Kernel.Object.String', true, false);
	$messageDefinition->addField('Destination', 'Kernel.Object.String', true, false);
	$messageDefinition->addField('MessageBody', 'Kernel.Object', true, false);
	
	$messageDefinition->addEvent('Recieve', 'Modules.FSManager.Object.Message');
	$messageDefinition->addEvent('Send', 'Modules.FSManager.Object.Message');
	
	$messageDefinition->addAction('BeforeSave', 'Modules.FSManager.Actions.UpdateSession');
	$messageDefinition->addAction('BeforeSave', 'Modules.FSManager.Actions.RouteMessage');
	$messageDefinition->addAction('BeforeSave', 'Kernel.Actions.Stop');//prevent saving an fsmanager message
	
	//$messageDefinition->addAction('AfterSave', 'Kernel.Actions.FireEvent', array($idCondition), array('EventName'=>'Recieve'));
	
	//$messageDefinition->addAction('Recieve', 'Modules.FSManager.Actions.UpdateSession');
	
	/*$messageDefinition->addAction(
		'Recieve', 
		'AuthenticateUser',
		array(
			'FieldName'=>'InputObject.Destination', 
			'Operator'=>'==', 
			'Value'=>'Modules.FSManager.Actions.AuthenticateUser'
		), 
		array(
			'Source'=>'InputObject', 
			'Destinations'=>array('InputObject.MessageBody')
		)
	);*/
	
	$objectList[] = $messageDefinition;

//Process the Installation Objects
	
	foreach($objectList as $object){
		$object->suspendEvents();
		echo '<tr><td>'.$object->getObjectName().'</td><td>'.($object->save()?'Saved': 'Save Failed').'</td></tr>';
	}
	
	$objectList = array();
	
	$guestAccount = $FSKernel->createObject('Kernel.Object.User');
	$guestAccount->setObjectName('FSManager Guest');
	$guestAccount->setObjectDescription('FSManager Guest Account');
	$guestAccount->setObjectAuthor('Flux Singularity Installer');
	$guestAccount->setObjectVersion('1.0.0');
	$guestAccount->setValue('Username', 'Guest');
	$guestAccount->setValue('Password', 'Guest');
	$guestAccount->setValue('DisplayName', 'Visitor');
	echo '<tr><td>'.$guestAccount->getObjectName().'</td><td>'.($guestAccount->save()?'Saved': 'Save Failed').'</td></tr>';
	
	$guestPermission = new KernelObject('Kernel.Object.Permission');
	$guestPermission->setObjectName('Kernel Global Permissions');
	$guestPermission->setObjectDescription('Permission Set applied to all Objects in Flux Singularity for Kernel Access');
	$guestPermission->setObjectAuthor('Flux Singularity Installer');
	$guestPermission->setObjectVersion('1.0.0');
	$guestPermission->setValue('Create', false);
	$guestPermission->setValue('Read', true);
	$guestPermission->setValue('Update', false);
	$guestPermission->setValue('Delete', false);
	$guestPermission->setValue('Recipient', $guestAccount);

	$loginButton = $FSKernel->createObject('Modules.FSManager.Object.Toolbar.Button');
	$loginButton->setObjectName('LoginButton');
	$loginButton->setObjectDescription('LoginButton');
	$loginButton->setObjectAuthor('Flux Singularity Installer');
	$loginButton->setValue('Text', 'Login');
	$loginButton->setValue('Icon', 'icon-lock');
	$loginButton->setValue('AppName', 'FSManager.applications.UserLogin');
	
	$guestToolbar = $FSKernel->createObject('Modules.FSManager.Object.Toolbar');
	$guestToolbar->setObjectName('Guest Toolbar');
	$guestToolbar->setObjectDescription('Guest Toolbar');
	$guestToolbar->setObjectAuthor('Flux Singularity Installer');
	$guestToolbar->setObjectVersion('1.0.0');
	$guestToolbar->setValue('Enabled', true);
	$guestToolbar->setValue('Title', 'Flux Singularity');
	$guestToolbar->addValue('Items', $loginButton);
	
	$guestViewport = $FSKernel->createObject('Modules.FSManager.Object.Viewport');
	$guestViewport->setObjectName('Guest Viewport');
	$guestViewport->setObjectDescription('Guest Viewport Object');
	$guestViewport->setObjectAuthor('Flux Singularity Installer');
	$guestViewport->setObjectVersion('1.0.0');
	$guestViewport->setValue('TopBar', $guestToolbar);
	
	//$guestViewport->addPermission($guestPermission);
	
	$objectList[] = $guestViewport;
	
	
	foreach($objectList as $object){
		echo '<tr><td>'.$object->getObjectName().'</td><td>'.($object->save()?'Saved': 'Save Failed').'</td></tr>';
	}
	echo '</table>';
?>