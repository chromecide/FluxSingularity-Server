<?php 
class KernelDataPrimitiveCondition extends KernelDataPrimitiveNamedList{
	public function __construct($data){
		parent::__construct($data);
		
		$this->_ClassName = 'Kernel.Data.Primitive.Condition';
		$this->_ClassTitle='Search Condition Primitive';
		$this->_ClassDescription = 'Condition Objects are used for searching entity data objects';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.7.0';
		
		$this->addItem('Attribute', null);
		$this->addItem('Operator', 	DataClassLoader::createInstance('Kernel.Data.Primitive.String', 	'=='));
		$this->addItem('Value', 	null);
		
		$this->loadData($data);
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