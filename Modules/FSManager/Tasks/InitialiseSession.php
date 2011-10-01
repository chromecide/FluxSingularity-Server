<?php
class ModulesFSManagerTasksInitialiseSession extends KernelTasksTask{
	public function __construct(){
		parent::__construct();
		$this->kernelClass='Modules.FSManager.Processes.InitialiseSession';
		$this->title='Initialise Session';
		$this->description='Initialise an FSManager User Session';
		$this->author='Justin Pradier <justin.pradier@fluxsingularity.com>';

		//Inputs
		$this->inputs['Client'] = array('Client', 'Kernel.Data.Primitive.String', true);
		$this->inputs['Message'] = array('Client', 'Modules.FSManager.Data.Message', true);
		
		//Outputs
		$this->outputs['SessionID'] = array('SessionID', 'Kernel.Data.Primitive.String');
	}

	public function runTask(){
		//Defaults

		//Load Inputs
		$Client = $this->getTaskInput('Client');
		$Message = $this->getTaskInput('Message');
		
		//create a new session object
		$sessionIDObj = '';
		
		//attach the session ID to the responses of the message
		
		//queue a message to initiate the viewport
		$viewportMessage = DataClassLoader::createInstance('Modules.FSManager.Data.Message');
		$viewportMessage->set('ApplicationID', $Message->get('ApplicationID'));
		$viewportMessage->set('MessageID', '');
		$viewportMessage->set('Module', 'FSManager');
		$viewportMessage->set('Process', 'InitialiseViewport');
		$viewportMessage->set('Status', DataClassLoader::createInstance('Kernel.Data.Primitive.String', 'New'));
		$viewportMessage->set('SessionID', $sessionIDObj);
		
		//Load the Default Viewport
		$viewport = DataClassLoader::createInstance('Modules.FSManagerData.Viewport');
		$viewport->set('Title', DataClassLoader::createInstance('Kernel.Data.Primitive.String', 'Flux Singularity'));
		$viewport->set('Background', DataClassLoader::createInstance('Kernel.Data.Primitive.String', '{}'));
		$viewport->set('TopBar', DataClassLoader::createInstance('Kernel.Data.Primitive.String', '{}'));
		$viewport->set('LeftBar', DataClassLoader::createInstance('Kernel.Data.Primitive.String', '{}'));
		$viewport->set('RightBar', DataClassLoader::createInstance('Kernel.Data.Primitive.String', '{}'));
		$viewport->set('BottomBar', DataClassLoader::createInstance('Kernel.Data.Primitive.String', '{}'));
		
		//build the message parameters
		$params = DataClassLoader::createInstance('Kernel.Data.Primitive.NamedList');
		$params->addItem('ClientID', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $Client->get()));
		$params->addItem('Viewport', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $viewport));
		
		$viewportMessage->set('Parameters', $params);
		
		$viewportMessage->save();
		parent::runTask();
	}
}
?>