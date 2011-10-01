<?php 
class ModulesFSManagerDataMessage extends KernelDataEntity{
	public function __construct($data){
		parent::__construct($data);
		
		$this->collectionName = 'FSManager.Messages';

		$this->_ClassName = 'Modules.FSManager.Data.Message';
		$this->_ClassTitle='FSManager Message Entity';
		$this->_ClassDescription = 'Used to create Entity Definitions';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '1.0.0';
		
		$this->fields['ApplicationID'] = array('ApplicationID', 'Kernel.Data.Primitive.String', true, false);
		$this->fields['MessageID'] = array('MessageID', 'Kernel.Data.Primitive.String', true, false);
		$this->fields['Module'] = array('Module', 'Kernel.Data.Primitive.String', true, false);
		$this->fields['Process'] = array('Process', 'Kernel.Data.Primitive.String', true, false);
		$this->fields['Parameters'] = array('Parameters', 'Kernel.Data.Primitive.NamedList', false, false);
		$this->fields['Status'] = array('Status', 'Kernel.Data.Primitive.String', true, false);
		$this->fields['SessionID'] = array('SessionID', 'Kernel.Data.Primitive.String', false, false);
		
		$this->fields['Responses'] = array('Response', 'Kernel.Data.Primitive.NamedList', false, true);
		
		$this->loadData($data);
	}
	
}
?>