<?php 
class KernelDataPrimitive extends KernelData{
	public function __construct($data){
		parent::__construct($data);
		
		$this->_ClassName = 'Kernel.Data.Primitive';
		$this->_ClassTitle='Kernel Primitive Data Base Object';
		$this->_ClassDescription = 'Primitive Data Objects are Simple Data Objects that, when saved, do not require a Unique ID.  Primitive Data Objects form the basis for Entity Attribute Values.';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.9.0';
	}
	
	public function setValue($value){
		parent::setValue($value);
		$this->data = $value;
		return true;
	}
	
	public function getValue(){
		return $this->data;
	}
}
?>