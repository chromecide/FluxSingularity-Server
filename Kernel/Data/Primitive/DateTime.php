<?php 
class KernelDataPrimitiveDateTime extends KernelDataPrimitive{
	public $defaultFormat = 'Y-m-d H:i:s';
	public $expectedFormats = array('Y-m-d H:i:s');
	public $namedFormats = array('en-au-date-time'=>'d/m/Y H:i:s');
	
	public function __construct($data=null){
		parent::__construct($data);
		$this->_ClassName = 'Kernel.Data.Primitive.DateTime';
		$this->_ClassTitle='Date and Time Primitive';
		$this->_ClassDescription = 'Used for storing date and time values';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.2.0';
		
		if(!$data){
			$this->data = strtotime('now');
		}else{
			$this->data = strtotime($data);
		}
	}
	
	public function setValue($value){
		//parse formats
		
		$this->data = $value;
	}
	
	public function getValue($format){
		if(!$format){
			$format = $this->defaultFormat;
		}
		
		if($format=='TIMESTAMP'){
			return $this->data;
		}else{
			return date($format, $this->data);
		}
	}
}

?>