<?php
class KernelDataMessagesMessage extends KernelDataEntity{
	public function __construct($data){
		parent::__construct(false);
		
		$this->collectionName = 'Kernel.Messaging.Messages';
		
		$this->_ClassName = 'Kernel.Data.Messages.Message';
		$this->_ClassTitle='Base Message Object';
		$this->_ClassDescription = 'Used to handle message passing throught the system';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.4.0';
		
		$this->fields['SourceID'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Source ID', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		$this->fields['DestinationID'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Destination ID', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>true));
		
		$this->fields['MessageID'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Message ID', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		$this->fields['Type'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Type', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		
		$this->fields['MessageData'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Message Data', 'Type'=>'Kernel.Data', 'Required'=>false, 'AllowList'=>true));
		
		$this->fields['PreviousMessage'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Previous Message', 'Type'=>'Kernel.Data.Messages.Message', 'Required'=>false, 'AllowList'=>false));
		
		$this->loadData($data);
	}
}
?>