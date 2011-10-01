<?php
require_once('../Kernel/DataClassLoader.php');
require_once('../Kernel/DataNormalisation.util.php');
require_once('../Kernel/DataValidation.util.php');

//Set the Paths to the Kernel and Modules folders
DataClassLoader::addPath('Kernel', realpath('../'));
DataClassLoader::addPath('Modules', realpath('../'));

$config = array(
	'KernelStore'=>array(
		'Driver'=>'MongoDB',
		'Database'=>'fluxs',
		'Username'=>'sysadmin',
		'Password'=>'syspass'
	),
	'User'=>array(
		'Username'=>'Kernel',
		'Password'=>"Kernel Passphrase Goes here y'all"
	)
);


$FSKernel = DataClassLoader::createInstance('Kernel', $config);

$type = $_GET['remotetype'];
$action = $_GET['remoteaction'];
$params = array();

if(!$_POST){
	foreach($_GET as $key=>$value){
		if($key!='remotetype' && $key !='remoteaction'){
			$params[$key] = $value;
		}
	}	
}else{
	print_r($_POST['Parameters']);
	echo 'No post support implemented<br/>';
}


$params['Enabled'] = true;

switch($type){
	case 'task':
		$task = $FSKernel->runTask($action, $params);
		$taskOutputs = $task->getOutputList();
		foreach($taskOutputs as $key=>$cfg){
			$value = $task->getTaskOutput($key);
			if($value){
				$return['data'][$key]= $value->getValue();
			}else{
				$return['data'][$key] = '';
			}
		}
		
		//header('Cache-Control: no-cache, must-revalidate');
		//header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		//header('Content-type: application/json');
		echo json_encode($return);
		break;
	case 'process':
		
		break;
	case 'event':
		
		break;
}
?>
