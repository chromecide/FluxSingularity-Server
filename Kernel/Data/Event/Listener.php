<?php 
class KernelDataEventListener extends KernelDataEntity{
	public function __construct($config){
		parent::__construct();
		
		$this->_ClassName = 'Kernel.Data.Event.Listener';
		$this->_ClassTitle='Event Listener';
		$this->_ClassDescription = 'Stores information about Event Listeners.';
		$this->_ClassAuthor = 'Justin Pradier [justin.pradier@fluxsingularity.com]';
		$this->_ClassVersion = '0.7.0';
		
		$this->collectionName = 'Kernel.Events.Listeners';
		
		$this->setValue('KernelClass', DataClassLoader::createInstance('Kernel.Data.Primitive.String', 'Kernel.Data.Event.Listener'));
		
		$this->fields['Event'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Event', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		
		$this->fields['Conditions'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Conditions', 'Type'=>'Kernel.Data.Primitive.ConditionGroup', 'Required'=>true, 'AllowList'=>false));
		$this->fields['Process'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Process', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		$this->fields['Enabled'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Enabled', 'Type'=>'Kernel.Data.Primitive.Boolean', 'Required'=>false, 'AllowList'=>false, 'DefaultValue'=>DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false)));
		$this->fields['InputMap'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'InputMap', 'Type'=>'Kernel.Data.Primitive.NamedList', 'Required'=>false, 'AllowList'=>false));
		
		$this->loadData($config);
	}
}
?>