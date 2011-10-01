<?php 
class ModulesFSManagerProcessesRouter extends KernelProcessesProcess{
	public function __construct($data){
		parent::__construct($data);
		
		$this->_ClassName = 'Modules.FSManager.Processes.Router';
		$this->_ClassTitle='FSManager Router Process';
		$this->_ClassDescription = 'This process handles messages to and from FSManger Clients';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '1.0.0';
		
		$this->buildTaskMap();
		
		parent::__construct();
	}
	
	public function buildTaskMap(){
		//if there is a source id, load it, otherwise create it
		
		//load the current user
		
		//if no user is found, send the command for the client to login
		
		
		//if a destination id was supplied, load it otherwise create it
		
		$createMessageTask = DataClassLoader::createInstance('Modules.FSManager.Tasks.CreateRouterMessage');
		$this->tasks['CreateMessage'] = $createMessageTask;
		
		
		$this->parameterMap['Inputs'][] = array('Enabled'=>'CreateMessage.Enabled');
		
		//save the message to the message queue
		$saveMessageTask = DataClassLoader::createInstance('Kernel.Tasks.Data.SaveEntity');
		$this->tasks['SaveMessage'] = $saveMessageTask;
		
		$this->inputEventMap['CreateMessage'][] = array('Message'=>'SaveMessage.Entity');
		
		
		$ifSaveMessageErrors = DataClassLoader::createInstance('Kernel.Tasks.Logic.If');
		$this->tasks['IfSaveMessageErrors'] = $ifSaveMessageErrors;
		
		$this->parameterMap['SaveMessage'][] = array('NotSaved'=>'IfSaveMessageErrors.Enabled');
		$this->parameterMap['SaveMessage'][] = array('Errors.count'=>'IfSaveMessageErrors.Input1');
		
		$this->setTokenData('IfSaveMessageErrors', 'Operator', new KernelDataPrimitiveString('>'));
		$this->setTokenData('IfSaveMessageErrors', 'Input2', new KernelDataPrimitiveNumber(0));
		
		$this->parameterMap['IfSaveMessageErrors'][] = array('Succeeded'=>'FormatErrors.Enabled');
		
		//task for outputting errors if applicable
		$formatErrorsTask = DataClassLoader::createInstance('Modules.FSManager.Tasks.FormatErrorResponse');
		
		$this->tasks['FormatErrors'] = $formatErrorsTask;
		
		$this->parameterMap['SaveMessage'][] = array('Entity'=>'FormatErrors.Message');
		$this->parameterMap['SaveMessage'][] = array('Errors'=>'FormatErrors.Errors');
		
		
		//load any messages that have a response value
		
		
		$outputMessagesTask = DataClassLoader::createInstance('Modules.FSManager.Tasks.OutputMessages');
		$this->tasks['OutputMessages'] = $outputMessagesTask;
		
		$this->parameterMap['FormatErrors'][] = array('Completed'=>'OutputMessages.Enabled');
		$this->parameterMap['FormatErrors'][] = array('Message'=>'OutputMessages.Messages');
		$this->parameterMap['SaveMessage'][] = array('Entity'=>'OutputMessages.Messages');
		$this->parameterMap['SaveMessage'][] = array('Saved'=>'OutputMessages.Enabled');
	}
}
?>