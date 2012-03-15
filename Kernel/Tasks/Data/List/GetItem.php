<?php 
/**
 * 
 * 
 * @author justin.pradier
 *
 */
class KernelTasksDataListGetItem extends KernelTasksTask{
	public function __construct(){
		parent::__construct(false);
		
		$this->_ClassName = 'Kernel.Tasks.Data.List.GetItem';
		$this->_ClassTitle='Get an Item from a List using the supplied index';
		$this->_ClassDescription = 'Joins Multiple Data Strings';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '1.0.0';
		
		$this->inputs['List'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'List Object', 'Type'=>'Kernel.Data.Primitive.List', 'Required'=>true, 'AllowList'=>false));
		$this->inputs['Index'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'List Index', 'Type'=>'Kernel.Data.Primitive.Number', 'Required'=>true, 'AllowList'=>false));
		
		$this->outputs['Item'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'List Item', 'Type'=>'Kernel.Data'));
		$this->outputs['ItemLoaded'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'List Item', 'Type'=>'Kernel.Data'));
		$this->outputs['ItemNotLoaded'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'List Item', 'Type'=>'Kernel.Data'));
	}
	
	public function run(){
		if(!parent::run()){
			return false;
		}
		
		$inputList = $this->getInputValue('List');
		$inputIndex = $this->getInputValue('Index');
		if($inputList instanceof KernelDataPrimitiveList){
			$this->setOutputValue('Item', $inputList->getItem());
			$this->setOutputValue('ItemLoaded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
			$this->setOutputValue('ItemNotLoaded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));
		}else{
			$this->setOutputValue('ItemLoaded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));
			$this->setOutputValue('ItemNotLoaded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
		}
		
		return $this->completeTask();
	}
	
	
}
?>