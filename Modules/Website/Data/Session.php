<?php
class ModulesWebsiteDataSession extends KernelDataEntity{
	public function __construct($data){
		parent::__construct($data);
		
		$this->collectionName = 'Modules.Website.Sessions';
		
		$this->_ClassName = 'Modules.Website.Data.Session';
		$this->_ClassTitle='Website Session Entity';
		$this->_ClassDescription = 'Used to track sessions with Flux Singularity Websites';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.5.0';
		
		$this->fields['SessionID'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Session ID', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		$this->fields['SessionTitle'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Session Title', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>false, 'AllowList'=>false));
		$this->fields['User'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'User', 'Type'=>'Kernel.Data.Security.User', 'Required'=>false, 'AllowList'=>false));
		
		$this->loadData($data);
	}
}
?>