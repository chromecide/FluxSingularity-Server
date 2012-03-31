<?php 
/**
 * 
 * 
 * @author justin.pradier
 *
 */
class KernelTasksDataListGetCount extends KernelTasksTask{
	public function __construct(){
		parent::__construct(false);
		
		$this->_ClassName = 'Kernel.Tasks.Data.List.GetCount';
		$this->_ClassTitle='Get List Count';
		$this->_ClassDescription = 'Gets the number of items in a Primitive List';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '1.0.0';
		
		$this->inputs['List'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'List Object', 'Type'=>'Kernel.Data.Primitive.List', 'Required'=>true, 'AllowList'=>false));
		
		$this->outputs['Count'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'List Count', 'Type'=>'Kernel.Data.Primitive.Number'));
	}
	
	public function run(){
		if(!parent::run()){
			return false;
		}
		
		$inputList = $this->getInputValue('List');
		
		if($inputList instanceof KernelDataPrimitiveList){
			$this->setOutputValue('Count', DataClassLoader::createInstance('Kernel.Data.Primitive.Number', $inputList->Count()));
		}else{
			$this->setOutputValue('Count', DataClassLoader::createInstance('Kernel.Data.Primitive.Number', 0));
		}
		
		return $this->completeTask();
	}
	
	
}
?>