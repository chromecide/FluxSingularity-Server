<?php
class ModulesWebsiteTasksSendJSONResponse extends KernelTasksTask{
	public function __construct(){
		parent::__construct();
		$this->kernelClass='Modules.Website.Tasks.SendJSONResponse';
		$this->title='Send JSON Response';
		$this->description='Send a JSON response to the client';
		$this->author='Justin Pradier <justin.pradier@fluxsingularity.com>';

		//Inputs

		$this->inputs['JSONString'] = array('JSONString', 'Kernel.Data.Primitive.String', true);
		//Outputs

	}

	public function runTask(){
		//Defaults

		//Load Inputs
		$JSONString = $this->getTaskInput('JSONString');

		//output the JSON
		header('Content-type: application/json');
		echo $JSONString->get();
		
		parent::runTask();
	}
}
?>