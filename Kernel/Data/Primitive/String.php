<?php 
Class KernelDataPrimitiveString extends KernelDataPrimitive{
	public function __construct($data){
		parent::__construct($data);
		
		$this->_ClassName = 'Kernel.Data.Primitive.String';
		$this->_ClassTitle ='String Primitive';
		$this->_ClassDescription = 'String Objects store a standard string of characters';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com>';
		$this->_ClassVersion = '1.0.0';
		
		$this->data = '';
		
		if($data){
			if($data instanceof KernelDataPrimitiveString){
				$this->data = $data->getValue();
			}else{
				$this->data = $data;
			}
			
		}
	}
	
	public function setValue($value){
		if($value instanceof KernelDataPrimitiveString){
			$this->data = $value->getValue();
		}else{
			$this->data = $value;
		}
		return true;
	}
}
?>