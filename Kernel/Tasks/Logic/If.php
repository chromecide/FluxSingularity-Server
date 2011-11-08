<?php 
/**
 * 
 * Wrapper for an If logic task
 * @author justin.pradier
 *
 */
class KernelTasksLogicIf extends KernelTasksTask{
	public function __construct($data){
		parent::__construct($data);
		
		$this->_ClassName = 'Kernel.Tasks.Logic.If';
		$this->_ClassTitle='Logical IF Task';
		$this->_ClassDescription = 'This task takes 2 inputs and a comparison operator and outputs whether the comparison succeeded';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.9.9';
		
		$this->inputs['Input1'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Input 1', 'Type'=>'Kernel.Data.Primitive', 'Required'=>false));
		$this->inputs['Operator'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Operator', 'Kernel.Data.Primitive.String',  'Required'=>false));
		$this->inputs['Input2'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Input 2', 'Type'=>'Kernel.Data.Primitive',  'Required'=>false));
		
		$this->outputs['Succeeded'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Succeeded', 'Type'=>'Kernel.Data.Primitive.Boolean'));
		$this->outputs['Failed'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Failed', 'Type'=>'Kernel.Data.Primitive.Boolean'));
	}
	
	public function run(){
		
		if(!parent::run()){
			return false;
		}
		
		$input1Obj = $this->getInputValue('Input1');
		$input2Obj = $this->getInputValue('Input2');
		$operatorObj = $this->getInputValue('Operator');
		
		$input1 = $input1Obj->getValue();
		
		$operator = '==';

		if($operatorObj){
			$operator = $operatorObj->getValue();	
		}
		
		$input2 = $input2Obj->getValue();
		
		$result = false;
		
		switch($operator){
			case "!=":
				$result = $input1!=$input2;
				break;
			case '>=':
				$result = $input1>=$input2;
				break;
			case '<=':
				$result = $input1<=$input2;
				break;
			case '>':
				$result = $input1>$input2;
				break;
			case '<':
				$result = $input1<$input2;
				break;
			case '@':
				$result = false;
				break;	
			case '==':
			default:
				$result = $input1==$input2;
				break;
		}
		
		$this->setTaskOutput('Succeeded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', $result));
		$this->setTaskOutput('Failed', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', !$result));
		
		return $this->completeTask();
	}
}
?>