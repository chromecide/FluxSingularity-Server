<?php 
class ModulesWebsiteDataSimpleSite extends KernelDataEntity{
	public function __construct($data){
		parent::__construct($data);
		
		$this->_ClassName = 'Modules.Website.Data.SimpleSite';
		$this->_ClassTitle='Website - Simple Site';
		$this->_ClassDescription = 'A Simple Website with a default header and footer';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.7.0';
		
		$this->collectionName = 'Website.Pages.Simple';
		
		$this->fields['Domain'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Domain', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		$this->fields['Title'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Title', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		$this->fields['Header'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Header', 'Type'=>'Modules.Website.Data.SimpleHeader', 'Required'=>true, 'AllowList'=>false));
		$this->fields['Footer'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Footer', 'Type'=>'Modules.Website.Data.SimpleFooter', 'Required'=>true, 'AllowList'=>false));
		$this->fields['Pages'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Pages', 'Type'=>'Modules.Website.Data.SimplePage', 'Required'=>true, 'AllowList'=>true));
		
		$this->loadData($data);
	}
}
?>