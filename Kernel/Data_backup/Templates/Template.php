<?php 
class KernelDataTemplatesTemplate extends KernelObject{
	protected $data = null;
	
	protected $fields = array();
	
	public function __construct($data){
		parent::__construct($data);
		
		$this->_ClassName = 'Kernel.Data.Templates.Template';
		$this->_ClassTitle = 'Kernel Data Template';
		$this->_ClassDescription = 'Kernel Data Base Template Object';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com>';
		$this->_ClassVersion = '0.4.0';
		
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
	
	
	public function addField($field){
		
	}
	
	public function updateField($fieldId, $field){
		
	}
}
?>