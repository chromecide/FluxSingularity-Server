<?php
require_once('../Kernel/DataClassLoader.php');
require_once('../Kernel/DataNormalisation.util.php');
require_once('../Kernel/DataValidation.util.php');

//Set the Paths to the Kernel and Modules folders
DataClassLoader::addPath('Kernel', realpath('../'));
DataClassLoader::addPath('Modules', realpath('../'));


//create a Kernel Instance
$FSKernel = DataClassLoader::createInstance('Kernel');


$EntityCfg = array(
	'Namespace'=>'Modules.Test.Data.TestDefinition',
	'Title'=>'Test Definition',
	'Description'=>'A test definition for loading entity data',
	'Author'=>'Justin Pradier',
	'Version'=>'0.8.0',
	'Fields'=>array(
		'StringAttribute'=>array(
			'Type'=>'Kernel.Data.Primitive.String',
			'Required'=>true,
			'AllowList'=>false
		),
		'NumberAttribute'=>array(
			'Type'=>'Kernel.Data.Primitive.Number',
			'Required'=>true,
			'AllowList'=>false
		),
	)
);

$entity = DataClassLoader::createInstance('Kernel.Data.Entity.Definition', $EntityCfg);

?>