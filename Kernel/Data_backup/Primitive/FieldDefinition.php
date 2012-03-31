<?php
class KernelDataPrimitiveFieldDefinition extends KernelDataPrimitiveNamedList{
	public function __construct($data){
		parent::__construct(false);
		
		$this->_ClassName = 'Kernel.Data.Primitive.FieldDefinition';
		$this->_ClassTitle='Error Primitive Object';
		$this->_ClassDescription = 'Used to create Error reports';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.9.9';
		
		$this->addItem('Name', null);
		$this->addItem('Type', 'Kerrnel.Data.Primitive.String');
		$this->addItem('Required', false);
		$this->addItem('AllowList', false);
		$this->addItem('DefaultValue', null);
		
		$this->loadData($data);
	}
	
	public function loadData($cfg){
		if(is_array($cfg)){
			
			if(array_key_exists('Name',$cfg)){
				$name = DataClassLoader::createInstance('Kernel.Data.Primitive.String', $cfg['Name']);
				$this->addItem('Name', $name);
			}
			
			if(array_key_exists('Type',$cfg)){
				$type = DataClassLoader::createInstance('Kernel.Data.Primitive.String', $cfg['Type']);
				$this->addItem('Type', $type);
			}
			
			if(array_key_exists('Required',$cfg)){
				$required = DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', $cfg['Required']);
				$this->addItem('Required', $required);
			}else{
				$required = DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false);
				$this->addItem('Required', $required);
			}
			
			if(array_key_exists('AllowList',$cfg)){
				$allowList = DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', $cfg['AllowList']);
				$this->addItem('AllowList', $allowList);
			}else{
				$allowList = DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false);
				$this->addItem('AllowList', $allowList);
			}
			
			if(array_key_exists('DefaultValue', $cfg)){
				$rawValue = $cfg['DefaultValue'];
				if(is_object($rawValue)){
					if(in_array('KernelData', class_parents($cfg['DefaultValue']))){
						$defaultValue = $rawValue;		
					}else{
						$defaultValue = DataClassLoader::createInstance($this->getItem('Type'), $rawValue);
					}
				}else{
					$defaultValue = DataClassLoader::createInstance($this->getItem('Type'), $rawValue);
				}
				
				$this->addItem('DefaultValue', $defaultValue);
			}		
		}
	}
}