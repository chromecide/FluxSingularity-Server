<?php 
/**
 * 
 * Wrapper for an AND logic task
 * @author justin.pradier
 *
 */
class KernelTasksLogicOr extends KernelTasksTask{
	public function __construct($inputVal1, $operator, $inputVal2){
		parent::__construct();
		
		$this->_ClassName = 'Kernel.Tasks.Logic.Or';
		$this->_ClassTitle='Logical OR Task';
		$this->_ClassDescription = 'Succeeds if any of the Inputs are true';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.8.0';
		
		
		$this->inputs['Inputs'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Input List', 'Type'=>'Kernel.Data.Primitive.Boolean', 'Required'=>true, 'AllowList'=>true));
		
		$this->outputs['Succeeded'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Succeeded', 'Type'=>'Kernel.Data.Primitive.Boolean'));
		$this->outputs['Failed'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Failed', 'Type'=>'Kernel.Data.Primitive.Boolean'));
	}
	
	public function runTask(){
		if(!parent::runTask()){
			return false;
		}
		
		$inputs = $this->getTaskInput('Inputs');
		$inputCount = $inputs->Count();
		$succeeded = false;
		
		for($i=0;$i<$inputCount;$i++){
			$item = $inputs->getItem($i+1);
			if($item){
				if($item->getValue()===true){
					$succeeded = true;
				}	
			}
		}
		
		if($succeeded){
			$this->setTaskOutput('Succeeded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
			$this->setTaskOutput('Failed', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));	
		}else{
			$this->setTaskOutput('Succeeded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));
			$this->setTaskOutput('Failed', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
		}
		
		return $this->completeTask();
	}
}
?>