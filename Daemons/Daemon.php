#!/usr/bin/php -q
<?php
/**
 * Daemon Initialisation
 */
date_default_timezone_set('Australia/ACT');

$runmode = array(
    'no-daemon' => false,
    'help' => false,
    'write-initd' => false,
);

// Scan command line attributes for allowed arguments
foreach ($argv as $k=>$arg) {
    if (substr($arg, 0, 2) == '--' && isset($runmode[substr($arg, 2)])) {
        $runmode[substr($arg, 2)] = true;
    }
}


// Help mode. Shows allowed argumentents and quit directly
if ($runmode['help'] == true) {
    echo 'Usage: '.$argv[0].' [runmode]' . "\n";
    echo 'Available runmodes:' . "\n";
    foreach ($runmode as $runmod=>$val) {
        echo ' --'.$runmod . "\n";
    }
    die();
}

error_reporting(E_ALL);
require_once 'System/Daemon.php';

// Setup
$options = array(
    'appName' => 'fskerneldaemon',
    'appDir' => dirname(__FILE__),
    'appDescription' => 'Runs Flux Singularity Pseudo-Daemons',
    'authorName' => 'Justin Pradier',
    'authorEmail' => 'justin.pradier@fluxsingularity.com',
    'sysMaxExecutionTime' => '0',
    'sysMaxInputTime' => '0',
    'sysMemoryLimit' => '512M',
    'appRunAsGID' => 1000,
    'appRunAsUID' => 1000,
);

System_Daemon::setOptions($options);


// This program can also be run in the forground with runmode --no-daemon
if (!$runmode['no-daemon']) {
    // Spawn Daemon
    System_Daemon::start();
}
 
// With the runmode --write-initd, this program can automatically write a
// system startup file called: 'init.d'
// This will make sure your daemon will be started on reboot
if (!$runmode['write-initd']) {
    System_Daemon::info('not writing an init.d script this time');
} else {
    if (($initd_location = System_Daemon::writeAutoRun()) === false) {
        System_Daemon::notice('unable to write init.d script');
    } else {
        System_Daemon::info(
            'sucessfully written startup script: %s',
            $initd_location
        );
    }
}

//include base classes
require_once '../Kernel/DataClassLoader.php';
require_once '../Kernel/DataNormalisation.util.php';
require_once '../Kernel/DataValidation.util.php';

//build the class loader path configs
DataClassLoader::addPath('Kernel', realpath('../'));
DataClassLoader::addPath('Modules', realpath('../'));

require_once '../Kernel/Kernel.php';

$config = array(
	'KernelStore'=>array(
		'Driver'=>'MongoDB',
		'Database'=>'fluxs'
	)
);

//create a Kernel Instance
$FSKernel = DataClassLoader::createInstance('Kernel', $config);

$daemons = array('Modules.FSManager.Daemons.MessageProcessor', 'Modules.FSManager.Daemons.SessionManager');

class KernelDaemonsDaemon{
	public function __construct(){
		
	}
	
	public function run(){
		
	}
}

$runningOkay = true;
$cnt=1;

while (!System_Daemon::isDying() && $runningOkay && $cnt <=3) {
	$mode = '"'.(System_Daemon::isInBackground() ? '' : 'non-' ).
        'daemon" mode';
	
	System_Daemon::info('{appName} running in %s %s/3',
		$mode,
		$cnt
	);
	
	foreach($daemons as $daemon){
		System_Daemon::info('Launching: %s',
			$daemon
		);
		
		$d = DataClassLoader::createInstance($daemon);
		
		$d->run();
		
		if (!$runningOkay) {
	        System_Daemon::err($d->getTitle().' produced an error, so this will be my last run');
	    }
	}
	
	System_Daemon::iterate(2);
 
    //$cnt++;
	
}

// Shut down the daemon nicely
// This is ignored if the class is actually running in the foreground
System_Daemon::stop();