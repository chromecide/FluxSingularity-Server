<?php
class KernelDataPrimitiveFieldDefinition extends KernelDataPrimitiveNamedList{
	public function __construct($data){
		parent::__construct();
		
		$this->_ClassName = 'Kernel.Data.Primitive.FieldDefinition';
		$this->_ClassTitle='Error Primitive Object';
		$this->_ClassDescription = 'Used to create Error reports';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.2.0';
		
		$this->addItem('Name', null);
		$this->addItem('Type', 'Kerrnel.Data.Primitive.String');
		$this->addItem('Required', false);
		$this->addItem('AllowList', false);
		$this->addItem('DefaultValue', null);
		
		$this->loadData($data);
	}
	
	public function loadData($cfg){
		if($cfg['Name']){
			$name = DataClassLoader::createInstance('Kernel.Data.Primitive.String', $cfg['Name']);
			$this->addItem('Name', $name);
		}
		
		if($cfg['Type']){
			$type = DataClassLoader::createInstance('Kernel.Data.Primitive.String', $cfg['Type']);
			$this->addItem('Type', $type);
		}
		
		if($cfg['Required']){
			$required = DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', $cfg['Required']);
			$this->addItem('Required', $required);
		}else{
			$required = DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false);
			$this->addItem('Required', $required);
		}
		
		if($cfg['AllowList']){
			$allowList = DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', $cfg['AllowList']);
			$this->addItem('AllowList', $allowList);
		}else{
			$allowList = DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false);
			$this->addItem('AllowList', $allowList);
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
				$defaultValue = DataClassLoader::createInstance($this->getItem('Type'), $rawValue);
			}
			
			$this->addItem('DefaultValue', $defaultValue);
		}
	}
}