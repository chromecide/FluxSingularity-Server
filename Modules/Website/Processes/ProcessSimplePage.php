<?php
class ModulesWebsiteProcessesProcessSimplePage extends KernelProcessesProcess{
	public function __construct($config){
		parent::__construct(false);
		
		$this->_ClassName = 'Modules.Website.Processes.SimpleWebsite';
		$this->_ClassTitle='Simple Website Process';
		$this->_ClassDescription = 'This process handles requests for Simple website pages';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '1.0.0';
		
		//Inputs
		$this->inputs['Domain'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Domain', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		$this->inputs['PagePath'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'PagePath', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		
		//outputs
		$this->outputs['HTML'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'HTML', 'Type'=>'Kernel.Data.Primitive.String'));
		
		$this->parseConfig($config);
		
		$this->buildTaskMap();
		
	}

	public function buildTaskMap(){
		$process = array(
			'Tasks'=>array(
				/*'ProcessHeader'=>'Kernel.Tasks.Data.String.ProcessKeyValueString',
				'ProcessFooter'=>'Kernel.Tasks.Data.String.ProcessKeyValueString',
				'AND1'=>'Kernel.Tasks.Logic.And',
				'JoinStrings1'=>'Kernel.tasks.Data.String.JoinStrings'*/
			),
			'TaskMap'=>array(
				/*'Inputs'=>array(
					'Enabled'=>array(
						'ProcessHeader.Enabled',
						'ProcessFooter.Enabled'
					),
					'Page.Header'=>array('ProcessHeader.String'),
					'Page.Content'=>array('JoinStrings1.Strings'),
					'Page.Footer'=>array('ProcessFooter.String')
				),
				'ProcessHeader'=>array(
					'StringProcessed'=>array(
						'AND1.Inputs'
					),
					'String'=>'JoinStrings1.Strings'
				),
				'ProcessFooter'=>array(
					'StringProcessed'=>array(
						'AND1.Inputs'
					),
					'String'=>'JoinStrings1.Strings'
				),
				'AND1'=>array(
					'Succeeded'=>array(
						'JoinStrings1.Enabled'
					)
				)*/
			),
		);
		
		$this->parseConfig($process);
	}
	
	
}
?>