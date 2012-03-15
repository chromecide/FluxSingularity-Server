<?php
class ModulesFSManagerProcessesRouteClientMessages extends KernelProcessesProcess{
	public function __construct($config){
		
		parent::__construct(false);
		
		$this->_ClassName = 'Modules.FSManager.Processes.RouteClientMessages';
		$this->_ClassTitle='Route FSManager Client Messages';
		$this->_ClassDescription = 'Routes FSManager Client Messages to the appropriate Destinations';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com>';
		$this->_ClassVersion = '1.0.0';
		
		//Inputs
		$this->inputs['Messages'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Username', 'Type'=>'Modules.FSManager.Data.Message', 'Required'=>true, 'AllowList'=>true));
		
		//outputs
		$this->outputs['MessagesProcessed'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Messages Processed', 'Type'=>'Kernel.Data.Primitive.Boolean'));
		$this->outputs['NoMessagesProcessed'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'No Messages Processed', 'Type'=>'Kernel.Data.Primitive.Boolean'));
		$this->outputs['NumberProcessed'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Message', 'Type'=>'Kernel.Data.Primitive.Number'));
		$this->outputs['NumberNotProcessed'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Message', 'Type'=>'Kernel.Data.Primitive.Number'));
		
		if($config){
			$this->parseConfig($config);	
		}
		
		$this->buildTaskMap();
	}
	
	public function buildTaskMap(){
		$process = array(
			'LocalData'=>array(
				'CurrentMessageIndex'=>DataClassLoader::createInstance('Kernel.Data.Primitive.Number', 0),
				'IncrementalValue'=>DataClassLoader::createInstance('Kernel.Data.Primitive.Number', 1),
				'IfOperatorEquals'=>DataClassLoader::createInstance('Kernel.Data.Primitive.String', '='),
				'IfDestinationIsKernel_ClassName'=>DataClassLoader::createInstance('Kernel.Data.Primitive.String', 'Kernel'),
				'IfTaskIsLogin_ClassName'=>DataClassLoader::createInstance('Kernel.Data.Primitive.String', 'Modules.FSManager.Processes.ProcessLogin'),
			),
			'Tasks'=>array(
				'GetMessageCount'=>'Kernel.Tasks.Data.List.GetCount',
				'IfAllMessagesProcessed'=>'Kernel.Tasks.Logic.If',
				'IncrementIndex'=>'Kernel.Tasks.Math.Add',
				'GetNextMessage'=>'Kernel.Tasks.Data.List.GetItem',
				'GetMessageDestination'=>'Kernel.Tasks.Data.GetEntityAttribute',
				'GetMessageTask'=>'Kernel.Tasks.Data.GetEntityAttribute',
				'IfDestinationIsKernel'=>'Kernel.Tasks.Logic.If',
				'IfTaskIsLogin'=>'Kernel.Tasks.Logic.If'
			),
			'TaskMap'=>array(
				'LocalData'=>array(
					'CurrentMessageIndex'=>array(
						'IncrementIndex.Input1',
						'IfAllMessagesProcessed.Input1'
					),
					'IncrementalValue'=>array(
						'IncrementIndex.Input2',
						'GetNextMessage.Index'
					),
					'IfOperatorEquals'=>array(
						'IfDestinationIsKernel.Operator',
						'IfTaskIsLogin.Operator',
					)
				),
				'Inputs'=>array(
					'Enabled'=>array(
						'GetMessageCount.Enabled'
					),
					'Messages'=>array(
						'GetMessageCount.List',
						'GetNextMessage.List'
					)
				),
				'GetMessageCount'=>array(
					'Completed'=>array(
						'IfAllMessagesProcessed.Enabled'
					),
					'Count'=>array(
						'IfAllMessagesProcessed.Input2'
					)
				),
				'IfAllMessagesProcessed'=>array(
					'Failed'=>array(
						'GetNextMessage.Reset'
					),
					'Succeeded'=>array(
						'Outputs.Completed'
					)
				),
				'GetNextMessage'=>array(
					'ItemLoaded'=>array(
						'GetMessageDestination.Enabled',
						'GetMessageTask.Enabled',
					),
					'ItemNotLoaded'=>array(
						'Outputs.Completed'
					),
					'Item'=>array(
						'GetMessageDestination.Entity',
						'GetMessageTask.Entity'
					)
				),
				'IncrementIndex'=>array(
					'Completed'=>array(
						'IfAllMessagesProcessed.Reset'
					),
					'Result'=>array(
						'LocalData.CurrentMessageIndex'
					)
				),
				'GetMessageDestination'=>array(
					'AttributeLoaded'=>array(
						'IfDestinationIsKernel.Enabled'
					),
					'AttributeNotLoaded'=>array(
						'Outputs.Completed'
					),
					'AttributeValue'=>array(
						'IfDestinationIsKernel.Input2'
					)
				),
				'IfDestinationIsKernel'=>array(
					'Succeeded'=>array(
						'GetMessageTask.Enabled'
					),
					'Failed'=>array(
						'Outputs.Completed'
					)
				),
				'GetMessageTask'=>array(
					'AttributeLoaded'=>array(
						'IfTaskIsLogin.Enabled'
					),
					'AttributeNotLoaded'=>array(
						'Outputs.Completed'
					),
					'AttributeValue'=>array(
						'IfTaskIsLogin.Input2'
					)
				),
				'IfTaskIsLogin'=>array(
					'Succeeded'=>array(
						'IncrementIndex.Reset'
					),
					'Failed'=>array(
						'Outputs.Completed'
					)
				)
			)
		);

		$this->parseDefinition($process);
	}
}
?>