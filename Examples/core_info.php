<?php
require_once('../Kernel/DataClassLoader.php');
require_once('../Kernel/DataNormalisation.util.php');
require_once('../Kernel/DataValidation.util.php');

//Set the Paths to the Kernel and Modules folders
DataClassLoader::addPath('Kernel', realpath('../'));
DataClassLoader::addPath('Modules', realpath('../'));


//create a Kernel Instance
$FSKernel = DataClassLoader::createInstance('Kernel');

$process = array(
	'Definition'=>array(
		'Defaults'=>array(
			'Create Kernel File List.Format'=>DataClassLoader::createInstance('Kernel.Data.Primitive.String', 'HTML'),
			'Create Module File List.Format'=>DataClassLoader::createInstance('Kernel.Data.Primitive.String', 'HTML'),
		),
		'LocalData'=>array(
			'LoopCount'=>DataClassLoader::createInstance('Kernel.Data.Primitive.Number', 0)
		),
		'Inputs'=>array(
			'Format'=>array('Name'=>'Format', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>false, 'DefaultValue'=>'HTML'),
			'IncludeMeta'=>array('Name'=>'Include Meta', 'Type'=>'Kernel.Data.Primitive.Boolean', 'Required'=>true, 'DefaultValue'=>false),
			'Enabled'=>array('Name'=>'Enabled', 'Type'=>'Kernel.Data.Primitive.Boolean', 'Required'=>true, 'DefaultValue'=>false, 'AllowList'=>false)
		),
		'Tasks'=>array(
			'Create Kernel File List'=>'Kernel.Tasks.System.CreateKernelFileList',
			'Create Module File List'=>'Kernel.Tasks.System.CreateModuleFileList',
			'AND'=> 'Kernel.Tasks.Logic.And',
			'Join Strings'=>'Kernel.Tasks.Data.JoinStrings',
			'Output HTML'=>'Modules.Website.Tasks.SendHTMLResponse'
		),
		'TaskMap'=>array(
			'Inputs'=>array(
				'Format'=>array(
					'Create Kernel File List.Format',
					'Create Module File List.Format'
				),
				'IncludeMeta'=>array(
					'Create Kernel File List.IncludeMeta',
					'Create Module File List.IncludeMeta'
				),
				'Enabled'=>array(
					'Create Kernel File List.Enabled',
					'Create Module File List.Enabled',
					'AND.Enabled'
				)
			),
			'Create Kernel File List'=>array(
				'HTML'=>array(
					'Join Strings.Strings'
				),
				'Completed'=>array(
					'AND.Inputs'
				)
			),
			'Create Module File List'=>array(
				'HTML'=>array(
					'Join Strings.Strings'
				),
				'Completed'=>array(
					'AND.Inputs'
				)
			),
			'AND'=>array(
				'Succeeded'=>array(
					'Join Strings.Enabled'
				),
				'Failed'=>array(
					'AND.Reset'
				)
			),
			'Join Strings'=>array(
				'String'=>array(
					'Output HTML.HTMLString'
				),
				'Succeeded'=>array(
					'Output HTML.Enabled'
				)
			)
		)
	),
	'Inputs'=>array(
		//'Format'=>DataClassLoader::createInstance('Kernel.Data.Primitive.String', 'HTML'),
		'IncludeMeta'=>DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true),
		'Enabled'=>DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true)
	)
);

$FSKernel->runTempProcess($process);


function readableByteString($bytes){
    $units = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    $unitCount = 0;
    for(; $bytes > 1024; $unitCount++) $bytes /= 1024;
    return $bytes ." ". $units[$unitCount];
}

echo '<hr>Used: '.readableByteString(memory_get_peak_usage()).' Peak Memory';

?>