<?php
	
	require_once '../Kernel/DataClassLoader.php';
	require_once '../Kernel/DataNormalisation.util.php';
	require_once '../Kernel/DataValidation.util.php';
	
	
	//build the class loader path configs
	DataClassLoader::addPath('Kernel', realpath('../'));
	DataClassLoader::addPath('Modules', realpath('../'));
	
	require_once '../Kernel/Kernel.php';
	//load any command line arguments
	// -queues QUEUEID[, QUEUEID] 	Comma seperated list of queues to limit this instance to (default is all)
	// -sleep XX 					time to sleep between process (default is 5)
	$queues = array();
	$clients = array();
	$modules = array();
	$processes = array();
	$users = array();
	
	$query = DataClassLoader::createInstance('Kernel.Tasks.Messages.LoadMessages');
	
	/*
	 * Start Parameters
	 */
	
	//Queue
	if($queues && count($queues)>0){
		$qryQueue = DataClassLoader::createInstance('Kernel.Data.Messages.MessageQueue');
		$queueList = DataClassLoader::createInstance('Kernel.Data.Primitive.List');
		//created a list object from the queues
		foreach(!$queues as $queue){
			$tQueue = $qryQueue->loadById(DataClassLoader::createInstance('Kernel.Data.Primitive.String', $queue));
			$queueList->addItem($tQueue);
		}
		$query->setTaskInput('Queue', $queueList);
	}
	
	//ClientID
	
	//User
	
	//Module
	
	//Process
	
	
	
	//load any rules associated with the selected message
	
	//foreach rule
		//load the process associated with that rule
		
		//create the process object and assign the input vars
		
		//fire the process
		
		//assign the result to the message and udpate the status if appropriate
		
		//save the message back to the queue
		
	echo '<hr/>';
?>