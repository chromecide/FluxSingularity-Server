<?php 

class KernelDataSecurityPermission extends KernelDataEntity{
	public function __construct($params){
		parent::__construct($params);
		
		$this->_ClassName = 'Kernel.Data.Security.Permission';
		$this->_ClassTitle='Data Permission Object';
		$this->_ClassDescription = 'Used to set permissions on data objects';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.7.0';
		
		$this->collectionName = 'Kernel.Security.Permissions';
		$this->setValue('KernelClass', new KernelDataPrimitiveString('Kernel.Data.Security.Permission'));
		
		$this->fields['AllowedCircles'] = array('AllowedCircles', 'Kernel.Data.Security.Circle', false, true);
		$this->fields['AllowedUsers'] = array('AllowedUsers', 'Kernel.Data.Security.User', false, false);
		$this->fields['DeniedCircles'] = array('DeniedCircles', 'Kernel.Data.Security.Circle', false, false);
		$this->fields['DeniedUsers'] = array('DeniedUsers', 'Kernel.Data.Security.User', false, false, false);
		
		$this->loadData($params);
	}
}
?>