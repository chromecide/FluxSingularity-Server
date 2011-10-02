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
		$this->_ClassVersion = '0.8.0';
		
		if(!$data){
			$this->data = strtotime('now');
		}else{
			$this->setValue($data);
		}
	}
	
	public function setValue($value, $secValue=false){
		if($secValue){//the user has passed in a part to set i.e. Day, month, year etc
			
			if(in_array('KernelDataPrimtive', class_parents($secValue))){
				$secValue = $secValue->getValue();
			}
			$currentVal = $this->getValue('Y-m-d H:i:s');
			
			$parts = explode(' ', $currentVal);
			$dateParts = explode('-', $parts[0]);
			$timeParts = explode(':', $parts[1]);
			
			switch(strtolower($value)){
				case 'year':
					$dateParts[0] = $secValue;		
					break;
				case 'month':
					if($secValue>0 && $secValue<13){
						$dateParts[1] = $secValue;	
					}else{
						return false;
					}
					break;
				case 'day':
					if($secValue>0 && $secValue<32){
						$dateParts[2] = $secValue;
					}else{
						return false;
					}
					break;
				case 'hour':
					if($secValue>0 && $secValue<25){
						$timeParts[0] = $secValue;
					}else{
						return false;
					}
					break;
				case 'minute':
					if($secValue>0 && $secValue<61){
						$timeParts[1] = $secValue;
					}else{
						return false;
					}
					break;
				case 'second':
					if($secValue>0 && $secValue<61){
						$timeParts[2] = $secValue;
					}else{
						return false;
					}
					break;
				case 'millisecond':
					if($secValue>0 && $secValue<1001){
						
					}else{
						return false;
					}
					break;
			}
			if(is_array($timeParts)){
				$newValue = $dateParts[0].'-'.$dateParts[1].'-'.$dateParts[2].' '.$timeParts[0].':'.$timeParts[1].':'.$timeParts[2];
			}else{
				$newValue = $dateParts[0].'-'.$dateParts[1].'-'.$dateParts[2];
			}
			
			$this->data = strtotime($newValue);
			return true;
		}else{
			//parse formats
			if($value instanceof KernelDataPrimitiveDateTime){
				$this->data = $value->getValue('TIMESTAMP');
				return true;
			}else{
				//need to parse formats
				$this->data = strtotime($value);
				return true;
			}	
		}
		
	}
	
	public function getValue($format){
		if(!$format){
			$format = $this->defaultFormat;
		}
		
		switch(strtolower($format)){
			case 'year':
				return date('Y', $this->data);
				break;
			case 'month':
				return date('m', $this->data);
				break;
			case 'day':
				return date('d', $this->data);
				break;
			case 'hour':
				return date('H', $this->data);
				break;
			case 'minute':
				return date('i', $this->data);
				break;
			case 'second':
				return date('s', $this->data);
				break;
			case 'timestamp':
				return $this->data;
				break;
			default:
				return date($format, $this->data);	
				break;
		}
		
		if($format=='TIMESTAMP'){
			
		}else{
			
		}
	}
}

?>