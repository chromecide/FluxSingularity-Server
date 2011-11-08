<?php 
class KernelTasksDataStringProcessKeyValueString extends KernelTasksTask{
	public function __construct(){
		parent::__construct(false);
		
		$this->_ClassName = 'Kernel.Tasks.Data.String.ProcessKeyValueString';
		$this->_ClassTitle='Process Key Value String';
		$this->_ClassDescription = 'Process a string simple template strings using Key/Value pairs';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.4.0';

		//Inputs

		$this->inputs['String'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'String', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		$this->inputs['ValuePairs'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Value Pairs', 'Type'=>'Kernel.Data.Primitive.NamedList', 'Required'=>true, 'AllowList'=>false));
		//Outputs

		$this->outputs['String'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'ProcessedHTML', 'Type'=>'Kernel.Data.Primitive.String'));
		$this->outputs['StringProcessed'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'StringProcessed', 'Type'=>'Kernel.Data.Primitive.String'));
		$this->outputs['StringNotProcessed'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'StringNotProcessed', 'Type'=>'Kernel.Data.Primitive.String'));
	}

	public function runTask(){
		if(!parent::runTask()){
			return false;
		}
		
		$Template = $this->getTaskInput('Template');
		$Values = $this->getTaskInput('ValuePairs');

		$values = $Values->toBasicObject();
		
		$processedString = $Template;
		foreach($values as $key=>$value){
			preg_replace('/{'.$key.'}/', $value, $processedString);
		}
		
		$this->setTaskOutput('String', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $processedString));
		
		$this->setTaskOutput('StringProcessed', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
		$this->setTaskOutput('StringNotProcessed', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));
		
		$this->completeTask();
	}
}
?>