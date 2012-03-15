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
		
	}
	
	public function buildTaskMap(){
		
		$process = array(
			'LocalData'=>array(
				
			),
			'Tasks'=>array(
				'LoadRouterMessages'=>'Modules.FSManager.Tasks.LoadRouterMessage',
				'RouteClientMessages'=>'Modules.FSManager.Processes.RouteClientMessages'
			),
			'TaskMap'=>array(
				'Inputs'=>array(
					'Enabled'=>array(
						'LoadRouterMessages.Enabled'
					)
				),
				'LoadRouterMessages'=>array(
					'Completed'=>array(
						'RouteClientMessages.Enabled'
					),
					'Messages'=>array(
						'RouteClientMessages.Messages'
					)
				),
				'RouteClientMessages'=>array(
					'Completed'=>array(
						'Outputs.Completed'
					)
				)
			)
		);
		
		$this->parseDefinition($process);
	}
}
?>