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
		$process = array(
			'LocalData'=>array(
				
			),
			'Tasks'=>array(
				
			),
			'TaskMap'=>array(
			
			)
		);
		
		$this->parseDefinition($process);
	}
}
?>