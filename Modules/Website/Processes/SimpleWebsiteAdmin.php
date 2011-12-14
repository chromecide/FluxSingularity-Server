<?php
class ModulesWebsiteProcessesSimpleWebsiteAdmin extends KernelProcessesProcess{
	public function __construct($config){
		
		parent::__construct(false);
		
		$this->_ClassName = 'Modules.Website.Processes.SimpleWebsiteAdmin';
		$this->_ClassTitle='Simple Website Admin Process';
		$this->_ClassDescription = 'This process handles requests for Simple website admin pages';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
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
				'Session'=>null,
				'User'=>null
			),
			'Tasks'=>array(
				'LoadSession'=>'Modules.Website.Tasks.LoadSession',
				'GetSessionUser'=>'Kernel.Tasks.Data.GetEntityAttribute',
				'ProcessLoginForm'=>'Modules.Website.Processes.ProcessLogin'
			),
			'TaskMap'=>array(
				'LocalData'=>array(
						
				),
				'Inputs'=>array(
					'Enabled'=>array(
						'LoadSession.Enabled'
					)
				),
				'LoadSession'=>array(
					'SessionLoaded'
				)
			)
			/*,
			'TaskMap'=>array(
				'LocalData'=>array(
					'ADMIN_URL'=>array(
						'IF1.Input1'
					),
					'ADMIN_MESSAGE'=>array(
						'LocalData.OUTPUT_HTML'
					),
					'OUTPUT_HTML'=>array(
						'OutputHTML.HTMLString'
					)
				),
				'Inputs'=>array(
					'Enabled'=>array(
						'IF1.Enabled'
					),
					'Domain'=>array(
						'LoadWebsitePage.Domain'
					),
					'PagePath'=>array(
						'IF1.Input2',
						'LoadWebsitePage.PagePath'	
					)
				),
				'IF1'=>array(
					'Failed'=>array(
						'LoadWebsitePage.Enabled'
					),
					'Succeeded'=>array(
						'LoadLoginForm.Enabled'
					)
				),
				'LoadLoginForm'=>array(
					'Completed'=>array(
						'OR1.Reset'
					),
					'DataNotLoaded'=>array(
						'OR1.Inputs'
					),
					'DataLoaded'=>array(
						'OR1.Inputs'
					)
				),
				'LoadWebsitePage'=>array(
					'Completed'=>array(
						'ProcessTemplate.Enabled'
					),
					'WebsitePage'=>array(
						'ProcessTemplate.Page'
					)
				),
				'OR1'=>array(
					'Succeeded'=>array(
						'OutputHTML.Enabled'
					)
				),
				'ProcessTemplate'=>array(
					'Completed'=>array(
						'OR1.Reset',
						'OR1.Inputs'
					),
					'HTML'=>array(
						'OutputHTML.HTMLString'
					)
				),
				'OutputHTML'=>array(
					'Completed'=>array(
						'Outputs.Completed'
					)
				)
			)*/
		);
		echo 'here admin';
		$this->parseDefinition($process);
	}
}
?>