<?php 
class KernelDataPrimitiveCondition extends KernelDataPrimitive{
	public function __construct($data){
		parent::__construct($data);
		
		$this->_ClassName = 'Kernel.Data.Primitive.Condition';
		$this->_ClassTitle='Search Condition Primitive';
		$this->_ClassDescription = 'Condition Objects are used for searching entity data objects';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.8.0';
		
		$this->fields[] = array('Attribute', 'Kernel.Data.Primitive.String', true, false);
		$this->fields[] = array('Operator', 'Kernel.Data.Primitive.String', true, false);
		$this->fields[] = array('Value', 'Kernel.Data.Primitive', true, false);
		
		$this->loadData($data);
	}
	
	public function setValue($field, $value){
		$this->data[$field] = $value;
	}
	
	public function getValue($field){
		return $this->data[$field];
	}
	
	public function loadData($data){
		if(is_array($data)){
			foreach($data as $key=>$value){
				switch($key){
					case 'Attribute':
					case 'Operator':
					case 'Value':
						if(is_object($value)){
							if(in_array('KernelDataPrimitive', class_parents($value))){
								$this->setValue($key, $value);
							}	
						}else{
							$this->setValue($key, DataClassLoader::createInstance('Kernel.Data.Primitive.String', $value));
						}
						break;
				}
			}
		}
	}
}
?>