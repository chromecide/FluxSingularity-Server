<?php

class ModulesWebsiteProcessesSimpleWebsite extends KernelProcessesProcess{
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
				'LoadWebsitePage'=>'Modules.Website.Tasks.LoadSimplePage',
				'ProcessTemplate'=>'Modules.Website.Tasks.ProcessBasicTemplate',	
				'OutputHTML'=>'Modules.Website.Tasks.SendHTMLResponse'
			),
			'TaskMap'=>array(
				'Inputs'=>array(
					'Enabled'=>array(
						'LoadWebsitePage.Enabled'
					),
					'Domain'=>array(
						'LoadWebsitePage.Domain'
					),
					'PagePath'=>array(
						'LoadWebsitePage.PagePath'
					)
				),
				'LoadWebsitePage'=>array(
					'PageLoaded'=>array(
						'ProcessTemplate.Enabled'
					),
					'WebsitePage'=>array(
						'ProcessTemplate.Page'
					)
				),
				'ProcessTemplate'=>array(
					'PageProcessed'=>array(
						'OutputHTML.Enabled'
					),
					'HTML'=>array(
						'OutputHTML.HTMLString'
					)
				),
				'OutputHTML'=>array(
					'Completed'=>array(
						//'Outputs.Completed'
					)
				)
			)
		);
		
		$this->parseDefinition($process);
	}
}
?>