<?php 
class KernelDataMessagesQueue extends KernelDataEntity{
	public function __construct($data){
		parent::__construct($data);
		
		$this->collectionName = 'Kernel.Messaging.Queues';
		
		$this->_ClassName = 'Kernel.Data.Messages.Queue';
		$this->_ClassTitle='Base Message Object';
		$this->_ClassDescription = 'Used to handle message passing throught the system';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.5.0';
		
		$this->fields['Type'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Type', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		$this->fields['Messages'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Messages', 'Type'=>'Kernel.Data.Messages.Message', 'Required'=>false, 'AllowList'=>true));
		
		$this->loadData($data);
	}
}
?>