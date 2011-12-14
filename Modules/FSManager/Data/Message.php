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
		
		$this->fields['ApplicationId'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Application ID', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		$this->fields['MessageId'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Message ID', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		$this->fields['Task'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Task', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		$this->fields['Source'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Source', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		$this->fields['Destination'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Destination', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		$this->fields['Inputs'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Inputs', 'Type'=>'Kernel.Data.Primitive.NamedList', 'Required'=>true, 'AllowList'=>false));
		$this->fields['Status'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Status', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		$this->fields['Responses'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'responses', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		
		$this->loadData($data);
	}

	public function loadData($data){
		
		if(is_object($data)){
			$data = get_object_vars($data);
		}
		
		parent::loadData($data);
		
		if(array_key_exists('ApplicationId', $data)){
			$this->setValue('ApplicationId', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $data['ApplicationId']));
		}

		if(array_key_exists('MessageId', $data)){
			$this->setValue('MessageId', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $data['MessageId']));
		}

		if(array_key_exists('Task', $data)){
			$this->setValue('Task', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $data['Task']));
		}
		
		if(array_key_exists('Source', $data)){
			$this->setValue('Source', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $data['Source']));
		}
		
		if(array_key_exists('Destination', $data)){
			$this->setValue('Destination', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $data['Destination']));
		}

		if(array_key_exists('Inputs', $data)){
			$this->setValue('Inputs', DataClassLoader::createInstance('Kernel.Data.Primitive.NamedList', $data['Inputs']));
		}

		if(array_key_exists('Status', $data)){
			$this->setValue('Status', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $data['Status']));
		}
		
		if(array_key_exists('Responses', $data)){
			$this->setValue('Responses', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $data['Responses']));
		}
	}
	
}
?>