<?php 
/**
 * 
 * Wrapper for an AND logic task
 * @author justin.pradier
 *
 */
class KernelTasksLogicOr extends KernelTasksTask{
	public function __construct($data){
		parent::__construct(false);
		
		$this->_ClassName = 'Kernel.Tasks.Logic.Or';
		$this->_ClassTitle='Logical OR Task';
		$this->_ClassDescription = 'Succeeds if any of the Inputs are true';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.9.9';
		
		
		$this->inputs['Inputs'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Input List', 'Type'=>'Kernel.Data.Primitive.Boolean', 'Required'=>true, 'AllowList'=>true));
		
		$this->outputs['Succeeded'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Succeeded', 'Type'=>'Kernel.Data.Primitive.Boolean'));
		$this->outputs['Failed'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Failed', 'Type'=>'Kernel.Data.Primitive.Boolean'));
	}
	
	public function run(){
		if(!parent::run()){
			return false;
		}
		
		$inputs = $this->getTaskInput('Inputs');
		
		if($inputs instanceof KernelDataPrimitiveList){
			$inputCount = $inputs->Count();	
		}else{
			if($inputs instanceof KernelDataPrimitiveBoolean){
				$inputCount=1;
				$inputItem = $inputs;
				$inputs = DataClassLoader::createInstance('Kernel.Data.Primitive.List');
				$inputs->addItem($inputItem);
			}
		}
		
		$succeeded = false;

		for($i=0;$i<$inputCount;$i++){
			$item = $inputs->getItem($i);
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
			echo 'or failed';
			$this->setTaskOutput('Succeeded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));
			$this->setTaskOutput('Failed', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
		}

		return $this->completeTask();
	}
}
?>