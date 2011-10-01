<?php 
class ModulesFSManagerDataSession extends KernelDataEntity{
	public function __construct($data){
		parent::__construct($data);
		$this->collectionName = 'FSManager.Sessions';
		
		$this->_ClassName = 'Modules.FSManager.Data.Session';
		$this->_ClassTitle='FSManager User Session Entity';
		$this->_ClassDescription = 'Used to create User Sessions';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '1.0.0';
		
		$this->fields['ViewportID'] = array('ViewportID', 'Kernel.Data.Primitive.String', false, false);
		$this->fields['DashboardIDs'] = array('DashboardIDs', 'Kernel.Data.Primitive.String', false, true);
		$this->fields['UserID'] = array('UserID', 'Kernel.Data.Primitive.String', false, true);
		$this->fields['RememberSession'] = array('RememberSession', 'Kernel.Data.Primitive.Boolean', false, false);
		
		$this->setValue('RememberSession', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));
		$this->loadData($data);
	}
}
?>