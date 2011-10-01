<?php 
/**
 * 
 * Join two Kernel.Data.Primitive.Strings and return a single item
 * @author justin.pradier
 *
 */
class KernelTasksDataJoinStrings extends KernelTasksTask{
	public function __construct($inputVal1, $operator, $inputVal2){
		parent::__construct();
		
		$this->_ClassName = 'Kernel.Tasks.Data.JoinString';
		$this->_ClassTitle='Join Data String';
		$this->_ClassDescription = 'Joins Multiple Data Strings';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.8.0';
		
		$this->inputs['Strings'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Strings', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>true));
		
		$this->outputs['String'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'String', 'Type'=>'Kernel.Data.Primitive.String'));
		$this->outputs['Succeeded'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Succeeded', 'Type'=>'Kernel.Data.Primitive.Boolean'));
		$this->outputs['Failed'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Failed', 'Type'=>'Kernel.Data.Primitive.Boolean'));
	}
	
	public function runTask(){
		if(!parent::runTask()){
			echo '<br/><Br/>Error<br/><Br/>';
			return false;
		}
		
		$inputs = $this->getTaskInput('Strings');
		$inputCount = $inputs->Count();
		
		$succeeded = true;
		$retString = '';
		for($i=0;$i<$inputCount;$i++){
			$item = $inputs->getItem($i);
			if($item){
				$retString.=$item->getValue();
			}else{
				$succeeded = false;
			}
		}

		if($succeeded){
			$this->setTaskOutput('String', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $retString));
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