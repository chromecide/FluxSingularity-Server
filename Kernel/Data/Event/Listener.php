<?php 
class KernelDataEventListener extends KernelDataEntity{
	public function __construct($config){
		
		parent::__construct($config);
		
		$this->collectionName = 'Kernel.Events.Listeners';
		$this->setValue('KernelClass', DataClassLoader::createInstance('Kernel.Data.Primitive.String', 'Kernel.Data.Event.Listener'));
		
		$this->fields['Event'] = array('Event', 'Kernel.Data.Primitive.String', true);
		
		$this->fields['Conditions'] = array('Conditions', 'Kernel.Data.Primitive.Condition', false, true);
		$this->fields['Process'] = array('Process', 'Kernel.Data.Primitive.String', true);
		$this->fields['Enabled'] = array('Enabled', 'Kernel.Data.Primitive.Boolean', true, false, false);
		$this->fields['InputMap'] = array('InputMap', 'Kernel.Data.Primitive.String', false, true);
		
		$this->loadData($config);
	}
}
?>