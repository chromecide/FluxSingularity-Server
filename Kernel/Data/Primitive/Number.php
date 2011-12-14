<?php 
class KernelDataPrimitiveNumber extends KernelDataPrimitive{
	public function __construct($data){
		parent::__construct($data);
		
		$this->_ClassName = 'Kernel.Data.Primitive.Number';
		$this->_ClassTitle='Number Primitive Object';
		$this->_ClassDescription = 'For creating Numerical Values';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '1.0.0';
		
		$this->data=0;
		
		//if($data!=null){
			if(is_numeric($data)){
				$this->data = $data;
			}
		//}
	}
	
	public function setValue($value){
		if(is_numeric($value)){
			$this->data = $value;
			return true;
		}else{
			if($value instanceof KernelDataPrimitiveNumber){
				$this->data = $value->getValue();
				return true;
			}else{
				return false;	
			}
		}
	}
}
?>