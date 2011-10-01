<?php 
class KernelData extends KernelObject{
	protected $data = null;
	
	public function __construct($data){
		parent::__construct($data);
		
		$this->_ClassName = 'Kernel.Data';
		$this->_ClassTitle = 'Kernel Data Base Object';
		$this->_ClassDescription = 'Kernel Data Base Object';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com>';
		$this->_ClassVersion = '0.8.0';
		
		$this->data = null;
	}
	
	/*
	 * Core Value Setter and Getter Functions
	 */
	public function getValue($name){
		return $this->data;
	}
	
	public function setValue($value){
		$this->data = $value;
	}
	
	public function getValueJSON(){
		$json = json_encode($this->data);
	}
	
}
?>