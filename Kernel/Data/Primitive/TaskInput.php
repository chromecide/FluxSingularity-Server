<?php
class KernelDataPrimitiveTaskInput extends KernelDataPrimitiveNamedList{
	public function __construct($data){
		parent::__construct();
		
		$this->_ClassName = 'Kernel.Data.Primitive.TaskInput';
		$this->_ClassTitle='Task Input Primitive';
		$this->_ClassDescription = 'String Objects store a standard string of characters';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com>';
		$this->_ClassVersion = '0.8.0';
		
		//$this->data = DataClassLoader::createInstance('Kernel.Data.Primitive.NamedList');	
		
		$this->addItem('Name', DataClassLoader::createInstance('Kernel.Data.Primitive.String', 'Input'));
		$this->addItem('Type', DataClassLoader::createInstance('Kernel.Data.Primitive.String', 'Kernel.Data.Primitive.String'));
		$this->addItem('Required', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));
		$this->addItem('AllowList', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));
		$this->addItem('DefaultValue', DataClassLoader::createInstance('Kernel.Data.Primitive.String'));
		
		$this->loadData($data);
	}
	
	public function loadData($cfg){
		if($cfg['Name']){
			$name = DataClassLoader::createInstance('Kernel.Data.Primitive.String', $cfg['Name']);
			$this->setValue('Name', $name);
		}
		
		if($cfg['Type']){
			$type = DataClassLoader::createInstance('Kernel.Data.Primitive.String', $cfg['Type']);
			$this->setValue('Type', $type);
		}
		
		if($cfg['Required']){
			$required = DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', $cfg['Required']);
			$this->setValue('Required', $required);
		}else{
			$required = DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false);
			$this->setValue('Required', $required);	
		}
		
		if($cfg['AllowList']){
			$allowList = DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', $cfg['AllowList']);
			$this->setValue('AllowList', $allowList);
		}else{
			$allowList = DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false);
			$this->setValue('AllowList', $allowList);
		}
		
		if($cfg['DefaultValue']){
			$rawValue = $cfg['DefaultValue'];
			if(is_object($rawValue)){
				if(in_array('KernelData', class_parents($cfg['DefaultValue']))){
					$defaultValue = $rawValue;		
				}else{
					$defaultValue = DataClassLoader::createInstance($this->getItem('Type'), $rawValue);
				}
			}else{
				$defaultValue = DataClassLoader::createInstance($this->getItem('Type')->getValue(), $rawValue);
			}
			
			$this->setValue('DefaultValue', $defaultValue);
		}
	}
}