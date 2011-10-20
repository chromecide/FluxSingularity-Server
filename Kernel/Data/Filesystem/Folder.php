<?php 
class KernelDataFilesystemFolder extends KernelDataEntity{
	public function __construct($config){
		parent::__construct(false);
		
		$this->_ClassName = 'Kernel.Data.Filesystem.Folder';
		$this->_ClassTitle='Kernel Base Folder Object';
		$this->_ClassDescription = 'Base Folder object for tracking Folder information';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.4.0';
		
		$this->collectionName = 'Kernel.Data.Filesystem.Files';
		
		$this->fields['Name'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Name', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		$this->fields['Path'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Path', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		$this->fields['Folders'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Folders', 'Type'=>'Kernel.Data.Filesystem.Folder', 'Required'=>false, 'AllowList'=>true));
		$this->fields['Files'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Folders', 'Type'=>'Kernel.Data.Filesystem.File', 'Required'=>false, 'AllowList'=>true));
		
		$this->loadData($config);
	}
	
	public function addFile($file){
		$files = $this->getValue('Files');
		
		if(!($files instanceof KernelDataPrimitiveNamedList)){
			$files = DataClassLoader::createInstance('Kernel.Data.Primitive.List');	
		}

		$files->addItem($file->getValue('Name')->getValue(), $file);
	}
}
?>