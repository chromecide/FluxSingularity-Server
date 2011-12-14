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
			'LocalData'=>array(
					'ADMIN_URL'=>DataClassLoader::createInstance('Kernel.Data.Primitive.String', '/admin'),
					'ADMIN_MESSAGE'=>DataClassLoader::createInstance('Kernel.Data.Primitive.String', '<form method="POST">username:<input type="text" name="Username"/><br/>Password<input type="password" name="Password" /><br/><input type="submit" value="Login"/></form>'),
					'OUTPUT_HTML'=>DataClassLoader::createInstance('Kernel.Data.Primitive.String', 'No Outout Supplied')
			),
			'Tasks'=>array(
				'If_is_Admin_Url'=>'Kernel.Tasks.Logic.If',
				'LoadWebsitePage'=>'Modules.Website.Tasks.LoadSimplePage',
				'DoSiteAdmin'=>'Modules.Website.Processes.SimpleWebsiteAdmin',
				'LoadLoginForm'=>'Modules.Website.Tasks.LoadPOSTData',
				'IsAdmin_or_PageIsProcessed'=>'Kernel.Tasks.Logic.Or',
				'ProcessTemplate'=>'Modules.Website.Tasks.ProcessBasicTemplate',
				'OutputHTML'=>'Modules.Website.Tasks.SendHTMLResponse'
			),
			'TaskMap'=>array(
				'LocalData'=>array(
					'ADMIN_URL'=>array(
						'If_is_Admin_Url.Input1'
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
						'If_is_Admin_Url.Enabled'
					),
					'Domain'=>array(
						'LoadWebsitePage.Domain'
					),
					'PagePath'=>array(
						'If_is_Admin_Url.Input2',
						'LoadWebsitePage.PagePath'	
					)
				),
				'If_is_Admin_Url'=>array(
					'Failed'=>array(
						'LoadWebsitePage.Enabled'
					),
					'Succeeded'=>array(
						'DoSiteAdmin.Enabled'
					)
				),
				'LoadLoginForm'=>array(
					'Completed'=>array(
						'IsAdmin_or_PageIsProcessed.Reset'
					),
					'DataNotLoaded'=>array(
						'IsAdmin_or_PageIsProcessed.Inputs'
					),
					'DataLoaded'=>array(
						'IsAdmin_or_PageIsProcessed.Inputs'
					)
				),
				'DoSiteAdmin'=>array(
					
				),
				'LoadWebsitePage'=>array(
					'Completed'=>array(
						'ProcessTemplate.Enabled'
					),
					'WebsitePage'=>array(
						'ProcessTemplate.Page'
					)
				),
				'IsAdmin_or_PageIsProcessed'=>array(
					'Succeeded'=>array(
						'OutputHTML.Enabled'
					)
				),
				'ProcessTemplate'=>array(
					'Completed'=>array(
						'IsAdmin_or_PageIsProcessed.Reset',
						'IsAdmin_or_PageIsProcessed.Inputs'
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
			)
		);
		
		$this->parseDefinition($process);
	}
}
?>