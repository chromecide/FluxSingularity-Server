<?php
class KernelTasksSystemGenerateKernelGithubReadme extends KernelTasksTask{
	public function __construct(){
		parent::__construct();
		
		$this->outputs['ReadmeText'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Readme Text', 'Type'=>'Kernel.Data.Primitive.String'));
		$this->outputs['ReadMeGenerated'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Readme Generated', 'Type'=>'Kernel.Data.Primitive.Boolean'));
	}
	
	public function runTask(){
		if(!parent::runTask()){
			return false;
		}
		
		$readMeText = '';
		$readMeText .= "# Flux Singularity\n";
		$readMeText .= "\n";
		$readMeText .= "\n";
		$readMeText .= "## Introduction\n";
		$readMeText .= "Flux Singularity is a series of frameworks allowing for the creation of applications through workflow and process management\n";
		$readMeText .= "\n";
		$readMeText .= "\n";
		$readMeText .= "### Kernel\n";
		$readMeText .= "Core Files Required to run Flux Singularity\n";
		$readMeText .= "\n";
		$readMeText .= "\n";
		$readMeText .= "#### Data\n";
		$readMeText .= "Core Files Required to handle Data within Flux Singularity\n";
		$readMeText .= "\n";
		$readMeText .= "\n";
		$readMeText .= "#### Tasks\n";
		$readMeText .= "Core library of tasks for use within Flux Singularity Processes\n";
		$readMeText .= "\n";
		$readMeText .= "\n";
		$readMeText .= "#### Processes\n";
		$readMeText .= "\n";
		$readMeText .= "\n";
		$readMeText .= "#### Daemons\n";
		$readMeText .= "\n";
		$readMeText .= "\n";
		$readMeText .= "## Installation\n";
		$readMeText .= "\n";
		$readMeText .= "\n";
		$readMeText .= "## Examples\n";
		echo $readMeText;
		
		$this->setTaskOutput('ReadmeText', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $readMeText));
		$this->completeTask();
	}
}
?>