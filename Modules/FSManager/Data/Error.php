<?php 
class ModulesFSManagerDataError extends KernelData{
	public function __construct($data){
		//$this->data = array();
		
		$this->fields['Message'] = array('Message', 'Kernel.Data.Primitive.String', true, false);
		$this->fields['Module'] = array('Message', 'Kernel.Data.Primitive.String', true, false);
		$this->fields['Task'] = array('Message', 'Kernel.Data.Primitive.String', true, false);
	}
	
	public function get($name){
		return $this->data[$name];
	}
	
	public function set($name, $value){
		$this->data[$name] = $value;
	}
}
?>