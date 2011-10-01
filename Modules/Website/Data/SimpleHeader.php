<?php 
class ModulesWebsiteDataSimpleHeader extends KernelDataEntity{
	public function __construct($data){
		parent::__construct($data);
		
		$this->_ClassName = 'Modules.Website.Data.SimpleHeader';
		$this->_ClassTitle='Website - Simple Header';
		$this->_ClassDescription = 'A Header for Simple Pages';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.7.0';
		
		$this->collectionName = 'Website.Pages.Simple';
		
		$this->fields['HTML'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'HTML', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		
		//$this->fields['PageHTML'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Page HTML', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>false, 'AllowList'=>false));
		
		$this->loadData($data);
	}
}
?>