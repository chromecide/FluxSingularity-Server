<?php
class KernelDataMessagesMessageSource extends KernelDataEntity{
	public function __construct($data){
		parent::__construct();
		
		$this->collectionName = 'Kernel.Messaging.MessageSources';
		
		$this->_ClassName = 'Kernel.Data.Messages.MessageSource';
		$this->_ClassTitle='Base Message Source Object';
		$this->_ClassDescription = 'Used for determining Source and Targets of Kernel Messages';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.4.0';
		
		$this->fields['Title'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Title', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		$this->fields['Application'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Application', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		$this->fields['ApplicationChildID'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'ApplicationChildID', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		$this->fields['Owner'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'User', 'Type'=>'Kernel.Data.Security.User', 'Required'=>true, 'AllowList'=>false));
		
		$this->loadData($data);
	}
}
?>