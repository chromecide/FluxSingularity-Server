<?php
class ModulesWebsiteProcessesProcessLogin extends KernelProcessesProcess{
	public function __construct($config){
		
		parent::__construct(false);
		
		$this->_ClassName = 'Modules.Website.Processes.ProcessLogin';
		$this->_ClassTitle='Process a HTML Form Login';
		$this->_ClassDescription = 'Processes a Basic HTML Login form.  The Field names must be "Username" and "Password"';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com>';
		$this->_ClassVersion = '1.0.0';
		
		//Inputs
		//$this->inputs['Domain'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Domain', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		//$this->inputs['PagePath'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'PagePath', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		
		//outputs
		$this->outputs['Page'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'HTML', 'Type'=>'Kernel.Data.Primitive.String'));
		
		$this->parseConfig($config);
		
		$this->buildTaskMap();
	}
	
	public function buildTaskMap(){
		$process = array(
			'LocalData'=>array(
				'IfLoginFormSubmitted_Operator'=>DataClassLoader::createInstance('Kernel.Data.Primitive.String','>'),
				'IfLoginFormSubmitted_Value2'=>DataClassLoader::createInstance('Kernel.Data.Primitive.Number', 0)
			),
			'Tasks'=>array(
				
				'LoadLoginForm'=>'Modules.Website.Tasks.LoadPOSTData',
				'IfLoginFormSubmitted'=>'Kernel.Tasks.Logic.If'
			),
			'TaskMap'=>array(
				'LocalData'=>array(
					'IfLoginFormSubmitted_Operator'=>'IfLoginFormSubmitted.Operator',
					'IfLoginFormSubmitted_Value2'=>'IfLoginFormSubmitted.Value2'
				),
				'Inputs'=>array(
					'Enabled'=>array(
						'LoadLoginForm.Enabled'
					)
				),
				'LoadLoginForm'=>array(
					'Completed'=>array(
						'IfLoginFormSubmitted.Enabled'
					),
					'Count'=>array(
						'IfLoginFormSubmitted.Count'
					)
				),
				'IfLoginFormSubmitted'=>array(
					'Succeeded'=>array(
						
					),
					'Failed'=>array(
					
					)
				)
			)
		);

		$this->parseDefinition($process);
	}
}
?>