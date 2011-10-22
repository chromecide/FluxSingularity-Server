<?php 
class ModulesWebsiteDataSimplePage extends KernelDataEntity{
	public function __construct($data){
		parent::__construct($data);
		
		$this->_ClassName = 'Modules.Website.Data.SimplePage';
		$this->_ClassTitle='Website - Simple Page';
		$this->_ClassDescription = 'A Simple Page is a list of HTML Blocks.';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.7.0';
		
		$this->collectionName = 'Website.Pages.Simple';
		
		$this->fields['Domain'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Domain', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		$this->fields['PagePath'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Page Path', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		$this->fields['Title'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Title', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		$this->fields['Header'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Header', 'Type'=>'Modules.Website.Data.SimpleHeader', 'Required'=>true, 'AllowList'=>false));
		$this->fields['Footer'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Footer', 'Type'=>'Modules.Website.Data.SimpleFooter', 'Required'=>true, 'AllowList'=>false));
		$this->fields['Content'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Content', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		
		//$this->fields['PageHTML'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Page HTML', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>false, 'AllowList'=>false));
		
		$this->loadData($data);
	}
}
?>