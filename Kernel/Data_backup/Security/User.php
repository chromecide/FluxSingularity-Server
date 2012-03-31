<?php 

class KernelDataSecurityUser extends KernelDataEntity{
	public function __construct($params){
		parent::__construct($params);
		
		$this->_ClassName = 'Kernel.Data.Security.User';
		$this->_ClassTitle='Security User';
		$this->_ClassDescription = 'Base system user, used for authenticating sessions';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.9.0';
		
		$this->collectionName = 'Kernel.Security.Users';
		
		$this->fields['Username'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Username', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		$this->fields['Password'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Password', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		$this->fields['EmailAddress'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Email Address', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		$this->fields['EmailActivated'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Email Activated', 'Type'=>'Kernel.Data.Primitive.Boolean', 'Required'=>true, 'AllowList'=>false, 'DefaultValue'=>DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean',false)));
		$this->fields['Circles'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Circles', 'Type'=>'Kernel.Data.Security.Circle', 'Required'=>false, 'AllowList'=>true));
		
		$this->loadData($params);
	}

	public function loadData($data){
		parent::loadData($data);
		
		if($data['Username']){
			$this->setValue('UserName', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $data['Username']));
		}

		if($data['Password']){
			$this->setValue('Password', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $data['Password']));
		}
		
		if($data['EmailAddress']){
			$this->setValue('EmailAddress', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $data['EmailAddress']));
		}
		
		if($data['EmailActivated']){
			$this->setValue('EmailActivated', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', $data['EmailActivated']));
		}

		$circleList = DataClassLoader::createInstance('Kernel.Data.Primitive.List');
		if($data['Circles']){
			if(is_array($data['Circles'])){
				foreach($data['Circles'] as $circle){
					$newCircle = DataClassLoader::createInstance('Kernel.Data.Security.Circle', $circle);
					$circleList->addItem($newCircle);
				}
			}
		}
		$this->setValue('Circles', $circleList);
	}

	public function beforeSave(){
		$username = $this->getValue('Username')->getValue();
		$kernelName = $username.' ['.$this->getValue('EmailAddress')->getValue().']';
		$this->setValue('KernelName', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $kernelName));
		
		$kernelDescription = 'User Account for: '.$username;
		$this->setValue('KernelDescription', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $kernelDescription));
		
		
		return true;
	}
}
?>