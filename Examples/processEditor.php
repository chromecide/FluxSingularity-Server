<?php
require_once('../Kernel/DataClassLoader.php');
require_once('../Kernel/DataNormalisation.util.php');
require_once('../Kernel/DataValidation.util.php');

//Set the Paths to the Kernel and Modules folders
DataClassLoader::addPath('Kernel', realpath('../'));
DataClassLoader::addPath('Modules', realpath('../'));


//create a Kernel Instance
$FSKernel = DataClassLoader::createInstance('Kernel');


?>