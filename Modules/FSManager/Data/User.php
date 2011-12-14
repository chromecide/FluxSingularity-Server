<?php
class ModulesFSManagerDataUser extends KernelDataEntity{
	public function __construct($data){
		if(!$data['extends']){
			$data['extends'] = array();	
		}
		$data['extends'][] = 'Kernel.Data.Security.User';
		
		parent::__construct($data);
		
		$this->collectionName = 'Modules.FSManager.Users';
		
		$this->_ClassName = 'Modules.FSManager.Data.User';
		$this->_ClassTitle='FSManager User';
		$this->_ClassDescription = 'FSManager User Account';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com>';
		$this->_ClassVersion = '0.5.0';
		
		$this->fields['DisplayName'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Display Name', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		
		$this->loadData($data);
	}
}
?>