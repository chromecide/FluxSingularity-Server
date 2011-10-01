<?php 
class ModulesWebsiteTasksProcessBasicTemplate extends KernelTasksTask{
	public function __construct(){
		parent::__construct();
		$this->kernelClass='Modules.Website.Tasks.ProcessBasicTemplate';
		$this->title='Process Basic Template';
		$this->description='Process a Basic Key-Value Template';
		$this->author='Justin Pradier <justin.pradier@fluxsingularity.com>';

		//Inputs

		$this->inputs['Template'] = array('Template', 'Modules.Website.Data.BasicTemplate', true);
		$this->inputs['Values'] = array('Values', 'Kernel.Data.Primitive.NamedList', true);
		//Outputs

		$this->outputs['ProcesseHTML'] = array('ProcesseHTML', 'Kernel.Data.Primitive.String');
	}

	public function runTask(){
		//Defaults

		//Load Inputs
		$Template = $this->getTaskInput('Template');
		$Values = $this->getTaskInput('Values');

		$values = $Values->toBasicObject();
		
		$processedHTML = $Template;
		foreach($values as $key=>$value){
			preg_replace('/{'.$key.'}/', $value, $processedHTML);
		}
		
		$this->setTaskOutput('ProcessedHTML', $processedHTML);
		parent::runTask();
	}
}
?>