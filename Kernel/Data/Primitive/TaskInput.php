<?php
class KernelDataPrimitiveTaskInput extends KernelDataPrimitiveNamedList{
	public function __construct($data){
		parent::__construct(false);
		
		$this->_ClassName = 'Kernel.Data.Primitive.TaskInput';
		$this->_ClassTitle='Task Input Primitive';
		$this->_ClassDescription = 'String Objects store a standard string of characters';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com>';
		$this->_ClassVersion = '0.8.0';
		
		$this->setValue('Name', 			DataClassLoader::createInstance('Kernel.Data.Primitive.String', 	'Input'));
		$this->setValue('Type', 			DataClassLoader::createInstance('Kernel.Data.Primitive.String', 	'Kernel.Data.Primitive.String'));
		$this->setValue('Required', 		DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', 	false));
		$this->setValue('AllowList', 		DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', 	false));
		$this->setValue('DefaultValue', 	DataClassLoader::createInstance('Kernel.Data.Primitive.String', 	null));
		
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
						$defaultValue = DataClassLoader::createInstance($this->getItem('Type')->getValue(), $rawValue);
					}
				}else{
					$defaultValue = DataClassLoader::createInstance($this->getItem('Type')->getValue(), $rawValue);	
				}
				
				$this->addItem('DefaultValue', $defaultValue);
			}
		}
	}
}