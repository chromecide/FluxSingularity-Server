<?php
class ModulesWebsiteDataSimpleTemplate extends KernelDataEntity{
	public function __construct($data){
		parent::__construct($data);
		
		$this->collectionName = 'Websites.Templates.Simple';
		
		$this->_ClassName = 'Modules.Website.Data.SimpleTemplate';
		$this->_ClassTitle='Website - Simple Template';
		$this->_ClassDescription = 'Simple Website Template Data Entity';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.5.0';
		
		$this->fields['Title'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Title', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		$this->fields['TemplateBody'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'TemplateBody', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		
		
		$this->loadData($data);
	}
}
?>