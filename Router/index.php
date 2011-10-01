<?php 
session_start();

define('FS_DEBUG', true);
define('FS_SYSTEM_BASEPATH', '/usr/local/zend/apache2/htdocs/fluxs.local/engine');
define('FS_SYSTEM_APPLICATIONID', 'System.API');

require_once FS_SYSTEM_BASEPATH.'/fluxsingularity.php';

require_once './Router.class.php';


if($_SESSION['UserID']){
	$userId = $_SESSION['UserID'];
}else{
	$userId = GUEST_USER_ID;
}

$router = new ApplicationRouter();
$returnMessages = array();


$returnMessages = $router->processRequest();

echo json_encode($returnMessages);
?>