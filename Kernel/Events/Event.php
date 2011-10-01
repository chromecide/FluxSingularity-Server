<?php 

class KernelEventsEvent extends KernelObject{
	//holds the collection of output Definitions
	protected $outputs = array();
	
	//holds the Collection of Output Data
	protected $outputData = array();
	
	public function __construct(){
		$this->_ClassName = 'Kernel.Events.Event';
		$this->_ClassTitle='Base Event Object';
		$this->_ClassDescription = 'The basis for all events within the Flux Singularity';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.3.0';
		
		$this->dataClassName = 'Kernel.Events.Event';
	}
	
	public function setOutputValue($name, $value){
		$this->outputData[$name] = $value;	
	}
	
	public function getOutputValue($name){
		$ret = $this->outputData[$name];
		return $ret;
	}
	
	//add the event to the database
	public function fire(){
		//load any listeners for this event
		foreach($this->outputs as $outputName=>$outputCfg){
			$value = $this->getOutputValue($outputName);
		}
	}
}
?>