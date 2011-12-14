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
				'IncrementalValue'=>DataClassLoader::createInstance('Kernel.Data.Primitive.Number', 1)
			),
			'Tasks'=>array(
				'GetMessageCount'=>'Kernel.Tasks.Data.List.GetCount',
				'IfAllMessagesProcessed'=>'Kernel.Tasks.Logic.If',
				'IncrementIndex'=>'Kernel.Tasks.Math.Add',
				'GetNextMessage'=>'Kernel.Tasks.Data.List.GetItem'
			),
			'TaskMap'=>array(
				'LocalData'=>array(
					'CurrentMessageIndex'=>array(
						'IncrementIndex.Input1',
						'IfAllMessagesProcessed.Input1',
						'GetNextMessage.Reset'
					),
					'IncrementalValue'=>array(
						'IncrementIndex.Input2'
					)
				),
				'Inputs'=>array(
					'Enabled'=>array(
						'GetMessageCount.Reset'
					),
					'Messages'=>array(
						'GetMessageCount.List'
					)
				),
				'GetMessageCount'=>array(
					'Count'=>array(
						'IfAllMessagesProcessed.Input2'
					)
				)/*,
				'IfAllMessagesProcessed'=>array(
					'Failed'=>array(
						'GetNextMessage.Reset'
					)
				),
				'GetNextMessage'=>array(
					'Completed'=>array(
						'IncrementMessageIndex.Reset'
					)
				),
				'IncrementMessageIndex'=>array(
					'Completed'=>array(
						'IfAllMessagesProcessed.Reset'
					)
				)*/
			)
		);

		$this->parseDefinition($process);
	}
}
?>