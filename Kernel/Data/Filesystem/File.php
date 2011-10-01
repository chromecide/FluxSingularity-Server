<?php 
class KernelDataFilesystemFile extends KernelDataEntity{
	public function __construct($config){
		parent::__construct();
		
		$this->_ClassName = 'Kernel.Data.Filesystem.File';
		$this->_ClassTitle='Kernel Base File Object';
		$this->_ClassDescription = 'Base File object for tracking file information';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.4.0';
		
		$this->collectionName = 'Kernel.Data.Filesystem.Files';
		
		$this->fields['Name'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Name', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		$this->fields['Path'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Path', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		
		$this->loadData($config);
	}
}
?>