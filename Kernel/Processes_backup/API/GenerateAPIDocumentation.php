<?php
class KernelProcessesAPIGenerateAPIDocumentation extends KernelProcessesProcess{
	public function __construct(){
		parent::__construct(false);
		
		$this->_ClassName = 'Kernel.Processes.API.GenerateAPIDocumentation';
		$this->_ClassTitle='Generate API Documentation';
		$this->_ClassDescription = 'A Process to generate a zip file containing the json object configuration for the Flux Singularity API Documentation';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.1.0';
		
		$this->KernelClass='Modules.FSManager.Processes.InitialiseSession';
		$this->title='Initialise Session';
		$this->description='Initialise an FSManager User Session';
		$this->author='Justin Pradier <justin.pradier@fluxsingularity.com>';
		
		$this->outputs['PathToZipFile'] = array('PathToZipFile', 'Kernel.Data.Primitive.String');
	}
}