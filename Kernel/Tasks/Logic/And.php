<?php 
/**
 * 
 * Wrapper for an AND logic task
 * @author justin.pradier
 *
 */
class KernelTasksLogicAnd extends KernelTasksTask{
	public function __construct($data){
		parent::__construct(false);
		
		$this->_ClassName = 'Kernel.Tasks.Logic.And';
		$this->_ClassTitle='Logical AND Task';
		$this->_ClassDescription = 'Succeeds if all Inputs are true';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.9.9';
		
		$this->inputs['Inputs'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Input List', 'Type'=>'Kernel.Data.Primitive.Boolean', 'Required'=>true, 'AllowList'=>true));
		
		$this->outputs['Succeeded'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Succeeded', 'Type'=>'Kernel.Data.Primitive.Boolean', 'AllowList'=>false));
		$this->outputs['Failed'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Failed', 'Type'=>'Kernel.Data.Primitive.Boolean', 'AllowList'=>false));
	}
	
	public function run(){
		if(!parent::run()){
			return false;
		}
		
		$inputs = $this->getInputValue('Inputs');
		
		$inputCount = $inputs->Count();
		
		$succeeded = true;
		
		for($i=0;$i<$inputCount;$i++){
			$item = $inputs->getItem($i);
			if($item){
				if($item->getValue()!==true){
					$succeeded = false;
				}	
			}else{
				$succeeded = false;
			}
		}
		
		if($succeeded){
			$this->setOutputValue('Succeeded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
			$this->setOutputValue('Failed', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));	
		}else{
			$this->setOutputValue('Succeeded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));
			$this->setOutputValue('Failed', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
		}
		
		return $this->completeTask();
	}
}
?>