<?php 
class KernelDataPrimitiveBoolean extends KernelDataPrimitive{
	public function __construct($data){
		parent::__construct($data);
		
		$this->_ClassName = 'Kernel.Data.Primitive.Boolean';
		$this->_ClassTitle='Boolean Primitive Object';
		$this->_ClassDescription = 'Boolean Objects store a true/false yes/no value';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com>';
		$this->_ClassVersion = '1.0.0';
		
		$this->data = false;
		
		if($data!==null){
			if($data instanceof KernelDataPrimitiveBoolean){
				$this->data = DataNormalization::doBoolean($data->getValue());
			}else{
				$this->data = DataNormalization::doBoolean($data);	
			}
		}
	}
	
	public function setValue($value){
		if($data instanceof KernelDataPrimitiveBoolean){
			$this->data = DataNormalization::doBoolean($data->getValue());
		}else{
			$this->data = DataNormalization::doBoolean($data);	
		}
		return true;
	}
}
?>