<?php 
class KernelDataPrimitiveTaskCondition extends KernelDataPrimitive{
	public function __construct($data){
		parent::__construct($data);
		
		$this->_ClassName = 'Kernel.Data.Primitive.TaskCondition';
		$this->_ClassTitle='Error Primitive Object';
		$this->_ClassDescription = 'Used to create Error reports';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.1.0';
		
		$this->fields[] = array('Attribute', 'Kernel.Data.Primitive.String', true, false);
		$this->fields[] = array('Operator', 'Kernel.Data.Primitive.String', true, false);
		$this->fields[] = array('Value', 'Kernel.Data.Primitive', true, false);
		$this->fields[] = array('Continuation', 'Kernel.Data.Primitive.String', true, false);
	}
	
	public function set($field, $value){
		$this->data[$field] = $value;
	}
	
	public function get($field){
		return $this->data[$field];
	}
}
?>