<?php 
class KernelDataPrimitiveNumber extends KernelDataPrimitive{
	public function __construct($data){
		parent::__construct($data);
		
		$this->_ClassName = 'Kernel.Data.Primitive.Number';
		$this->_ClassTitle='Number Primitive Object';
		$this->_ClassDescription = 'For creating Numerical Values';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.4.0';
		
		$this->data=0;
		if($data){
			if(is_numeric($data)){
				$this->data = $data;
			}
		}
	}
}
?>