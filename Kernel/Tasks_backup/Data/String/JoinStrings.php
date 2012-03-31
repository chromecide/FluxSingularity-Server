<?php 
/**
 * 
 * Join two Kernel.Data.Primitive.Strings and return a single item
 * @author justin.pradier
 *
 */
class KernelTasksDataStringJoinStrings extends KernelTasksTask{
	public function __construct(){
		parent::__construct(false);
		
		$this->_ClassName = 'Kernel.Tasks.Data.JoinString';
		$this->_ClassTitle='Join Data String';
		$this->_ClassDescription = 'Joins Multiple Data Strings';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '1.0.0';
		
		$this->inputs['Strings'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Strings', 'Type'=>'Kernel.Data.Primitive', 'Required'=>true, 'AllowList'=>true));
		
		$this->outputs['String'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'String', 'Type'=>'Kernel.Data.Primitive.String'));
		$this->outputs['Succeeded'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Succeeded', 'Type'=>'Kernel.Data.Primitive.Boolean'));
		$this->outputs['Failed'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Failed', 'Type'=>'Kernel.Data.Primitive.Boolean'));
	}
	
	public function run(){
		if(!parent::run()){
			return false;
		}
		
		$inputs = $this->getInputValue('Strings');
		
		if($inputs instanceof KernelDataPrimitiveList){
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
			
			//echo 'Joined: '.$retString.'<br/>';
			
			if($succeeded){
				$this->setOutputValue('String', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $retString));
				$this->setOutputValue('Succeeded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
				$this->setOutputValue('Failed', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));	
			}else{
				
				$this->setOutputValue('Succeeded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));
				$this->setOutputValue('Failed', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
			}	
		}else{
			return false;
		}
		return $this->completeTask();
	}
	
	
}
?>