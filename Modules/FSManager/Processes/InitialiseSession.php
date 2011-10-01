<?php
class ModulesFSManagerProcessesInitialiseSession extends KernelProcessesProcess{
	public function __construct(){
		parent::__construct();
		$this->KernelClass='Modules.FSManager.Processes.InitialiseSession';
		$this->title='Initialise Session';
		$this->description='Initialise an FSManager User Session';
		$this->author='Justin Pradier <justin.pradier@fluxsingularity.com>';

		//Inputs
		$this->inputs['Client'] = array('Client', 'Kernel.Data.Primitive.String', true);
		$this->inputs['Message'] = array('', 'Modules.FSManager.Data.Message', true);
		
		//Outputs
		$this->outputs['SessionID'] = array('SessionID', 'Kernel.Data.Primitive.String');
		$this->buildTaskMap();
	}

	public function buildTaskMap(){
		$ifSessionIDSuppliedTask = DataClassLoader::createInstance('Kernel.Tasks.Logic.If');
		$ifSessionNotLoadedTask = DataClassLoader::createInstance('Kernel.Tasks.Logic.If');
		$loadSessionTask = DataClassLoader::createInstance('Modules.FSManager.Tasks.LoadSessionById');
		$createSessionTask = DataClassLoader::createInstance('Modules.FSManager.Tasks.CreateSession');
		$setSessionIDTask = DataClassLoader::createInstance('Kernel.Tasks.Data.SetEntityAttribute');
		$saveMessageTask = DataClassLoader::createInstance('Kernel.Tasks.Data.SaveEntity');
		
		$this->tasks['IfSessionIDSupplied'] = $ifSessionIDSuppliedTask;
		$this->tasks['LoadSession'] = $loadSessionTask;
		$this->tasks['IfSessionNotLoaded'] = $ifSessionNotLoadedTask;
		$this->tasks['CreateSession'] = $createSessionTask;
		$this->tasks['SetSessionID'] = $setSessionIDTask;
		$this->tasks['SaveMessage'] = $saveMessageTask;
		
		$this->parameterMap['Inputs'][] = array('Message'=>'SetSessionID.Entity');
		$this->parameterMap['Inputs'][] = array('Message.SessionID'=>'IfSessionIDSupplied.Input1');
		
		$this->setTokenData('IfSessionIDSupplied', 'Enabled', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
		$this->setTokenData('IfSessionIDSupplied', 'Operator', DataClassLoader::createInstance('Kernel.Data.Primitive.String', '!='));
		$this->setTokenData('IfSessionIDSupplied', 'Input2', DataClassLoader::createInstance('Kernel.Data.Primitive.String', ''));
		
		$this->parameterMap['IfSessionIDSupplied'][] = array('Succeeded'=>'LoadSession.Enabled');
		$this->parameterMap['Inputs'][] = array('Message.SessionID'=>'LoadSession.SessionID');
		
		$this->parameterMap['IfSessionIDSupplied'][] = array('Failed'=>'CreateSession.Enabled');
		
		$this->parameterMap['LoadSession'][] = array('SessionFound'=>'SetSessionID.Enabled');
		$this->parameterMap['LoadSession'][] = array('Session.KernelID'=>'SetSessionID.Value');
		
		$this->parameterMap['Inputs'][] = array('LoadSession.SessionNotFound'=>'IfSessionNotLoaded.Input1');
		$this->setTokenData('IfSessionNotLoaded', 'Enabled', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
		$this->setTokenData('IfSessionNotLoaded', 'Operator', DataClassLoader::createInstance('Kernel.Data.Primitive.String', '!='));
		$this->setTokenData('IfSessionNotLoaded', 'Input2', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
		
		$this->parameterMap['IfSessionNotLoaded'][] = array('Succeeded'=>'CreateSession.Enabled');
		
		$this->parameterMap['CreateSession'][] = array('SessionCreated'=>'SetSessionID.Enabled');
		$this->parameterMap['CreateSession'][] = array('Session.KernelID'=>'SetSessionID.Value');
		
		$this->parameterMap['SetSessionID'][] = array('AttributeSet', 'SaveMessage.Enabled');
		$this->parameterMap['SetSessionID'][] = array('Entity', 'SaveMessage.Entity');
		$this->setTokenData('SetSessionID', 'AttributeName', DataClassLoader::createInstance('Kernel.Data.Primitive.String', 'Responses'));
	}
}
?>