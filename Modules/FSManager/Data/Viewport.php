<?php 
class ModulesFSManagerDataViewport extends KernelDataEntity{
	public function __construct($data){
		parent::__construct($data);
		
		$this->collectionName = 'FSManager.Viewports';
		
		$this->fields['Title'] = array('Title', 'Kernel.Data.Primitive.String', true, false);
		$this->fields['Background'] = array('Background', 'Kernel.Data.Primitive.NamedList', true, false);
		$this->fields['TopBar'] = array('TopBar', 'Kernel.Data.Primitive.NamedList', true, false);
		$this->fields['BottomBar'] = array('BottomBar', 'Kernel.Data.Primitive.NamedList', true, false);
		$this->fields['LeftBar'] = array('LeftBar', 'Kernel.Data.Primitive.NamedList', true, false);
		$this->fields['RightBar'] = array('RightBar', 'Kernel.Data.Primitive.NamedList', true, false);
		$this->fields['ReadOnly'] = array('ReadOnly', 'Kernel.Data.Primitive.Boolean', true, false);
		
		$this->set('KernelClass', new KernelDataPrimitiveString('Modules.FSManager.Data.Viewport'));
		
		$this->loadData($data);
	}
	
}
?>