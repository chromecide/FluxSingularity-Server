<?php 

class KernelDataSecurityCircle extends KernelDataEntity{
	public function __construct($params){
		parent::__construct($params);
		
		$this->_ClassName = 'Kernel.Data.Security.Circle';
		$this->_ClassTitle='Security Circle Entity';
		$this->_ClassDescription = 'Used to store information about security circles';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.8.0';
		
		$this->collectionName = 'Kernel.Security.Circles';
		$this->setValue('KernelClass', new KernelDataPrimitiveString('Kernel.Data.Security.Circle'));
		
		$this->fields['Title'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Title', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		$this->fields['Description'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Description', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>false, 'AllowList'=>false));
		
		$this->fields['Owner'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Owner', 'Type'=>'Kernel.Data.Security.User', 'Required'=>true, 'AllowList'=>false));
		
		$this->fields['OptIn'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'OptIn', 'Type'=>'Kernel.Data.Primitive.Boolean', 'Required'=>false, 'AllowList'=>false, 'DefaultValue'=>DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean',false)));
		$this->fields['AllowedUsers'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'AllowedUsers', 'Type'=>'Kernel.Data.Security.User', 'Required'=>false, 'AllowList'=>true));
		$this->fields['DeniedUsers'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'DeniedUsers', 'Type'=>'Kernel.Data.Security.User', 'Required'=>false, 'AllowList'=>true));
		
		$this->loadData($params);
	}
	
	public function loadData($params){
		if($params['Title']){
			if($params['Title'] instanceof KernelDataPrimitiveString){
				$this->setValue('Title', $params['Title']);
			}else{
				$this->setValue('Title', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $params['Title']));	
			}
			
		}
		
		if($params['Description']){
			if($params['Description'] instanceof KernelDataPrimitiveString){
				$this->setValue('Description', $params['Description']);
			}else{
				$this->setValue('Description', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $params['Description']));	
			}
			
		}

		if($params['Owner']){
			if(in_array('KernelDataSecurityUser', class_parents($params['Owner']))){
				$this->setValue('Owner', $params['Owner']);	
			}else{
				//need to load user with supplied information
				echo 'NEED TO LOAD USER';
			}
			
		}

		if($params['OptIn']){
			if($params['OptIn'] instanceof KernelDataPrimitiveBoolean){
				$this->setValue('OptIn', $params['OptIn']);
			}else{
				$this->setValue('OptIn', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', $params['OptIn']));	
			}
			
		}

		if($params['AllowedUsers']){
			
		}
		
		if($params['DeniedUsers']){
			
		}

		parent::loadData($params);
	}
	
	public function beforeSave(){
		$title = $this->getValue('Title')->getValue();
		$kernelName = $title;
		$this->setValue('KernelName', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $kernelName));
		
		$kernelDescription = $this->getValue('Description')->getValue();
		$this->setValue('KernelDescription', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $kernelDescription));
		
		return true;	
	}
}
?>