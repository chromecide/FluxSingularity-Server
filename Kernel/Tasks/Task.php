<?php 

/**
 * 
 * Base Object for all system tasks
 * @author justin.pradier
 *
 */
class KernelTasksTask extends KernelObject{
	protected $kernelClass;
	protected $inputs = array();
	protected $outputs = array();
	
	protected $inputData = array();
	protected $outputData = array();
	
	public function __construct($data){
		parent::__construct($data);
		
		$this->_ClassName = 'Kernel.Tasks.Task';
		$this->_ClassTitle='Kernel Task Base Object';
		$this->_ClassDescription = 'Base object for all tasks in the system';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.4.0';
		
		$enabledDef = array('Name'=>'Enabled', 'Type'=>'Kernel.Data.Primitive.Boolean', 'Required'=>true, 'DefaultValue'=>false, 'AllowList'=>false);
		$resetDef = array('Name'=>'Reset', 'Type'=>'Kernel.Data.Primitive.Boolean', 'Required'=>true, 'DefaultValue'=>false, 'AllowList'=>false);
		
		$this->inputs['Enabled'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', $enabledDef);
		$this->inputs['Reset'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', $resetDef);
		
		$completedDef = array('Name'=>'Completed', 'Type'=>'Kernel.Data.Primitive.Boolean', 'Required'=>true, 'DefaultValue'=>false, 'AllowList'=>false);
		$errorOcurredDef = array('Name'=>'Error Ocurred', 'Type'=>'Kernel.Data.Primitive.Boolean', 'Required'=>true, 'DefaultValue'=>false, 'AllowList'=>false);
		$errorsDef = array('Name'=>'Errors', 'Type'=>'Kernel.Data.Primitive.Error', 'Required'=>false, 'AllowList'=>true);
		
		$this->outputs['Completed'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', $completedDef);
		$this->outputs['ErrorOcurred'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', $errorOcurredDef);
		$this->outputs['Errors'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', $errorsDef);
		
		
		$this->setTaskOutput('Completed', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', $completed));
		$this->setTaskOutput('ErrorOcurred', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', $errored));
		$this->setTaskOutput('Errors', DataClassLoader::createInstance('Kernel.Data.Primitive.List'));
	}
	
	public function runTask(){
		if(!$this->isReady()){
			return false;
		}else{
			if(!$this->validateInputs()){
				$this->completeTask();
			}else{
				return true;
			}
		}
		
	}
	
	public function completeTask(){
		
		$completed = true;
		$errored = false;

		if($this->getErrorCount()>0){
			echo 'Errors: '.$this->getErrorCount().'<br/>';
			$completed = false;
			$errored = true;
		}

		$this->setTaskOutput('Completed', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', $completed));
		$this->setTaskOutput('ErrorOcurred', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', $errored));
		return true;
	}
	
	public function enableTask(){
		$this->setTaskInput('Enabled', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
	}
	
	public function isReady(){
		$enabled = $this->getTaskInput('Enabled');
		$completed = $this->getTaskOutput('Completed');
		$reset = $this->getTaskInput('Reset');
		
		if($enabled && $enabled->getValue()===true){
			if(!$completed || $completed->getValue()===false){
				return true;
			}else{
				if($reset && $reset->getValue()===true){
					return true;	
				}
			}
		}else{
			
			//echo $this->getClassName().' not enabled<br/>';
		}
		
		return false;
	}
	
	public function setTaskInput($input, $value){
		$field = $this->inputs[$input];
		
		if($field && $field->getValue('AllowList')){
			if($field->getValue('AllowList')->getValue()==true){
				$values = $this->getTaskInput($input);
				if($value instanceof KernelDataPrimitiveList){
					$values = $value;
				}
				//$values->addItem($value);
			}else{
				$values = $value;
			}
		}else{
			//echo 'no field for:'.$input.'<br/>';
			$values = $value;
		}
		$this->inputData[$input] = $values;
	}
	
	public function getTaskInput($input){
		return $this->inputData[$input];
	}
	
	public function setTaskOutput($output, $value){
		$this->outputData[$output] = $value;
	}
	
	public function getTaskOutput($output){
		if(array_key_exists($output, $this->outputData)){
			return $this->outputData[$output];
		}else{
			return null;
		}
	}
	
	public function getInputDefinition($name){
		return $this->inputs[$name];
	}
	public function getInputList(){
		return $this->inputs;
	}
	
	public function getOutputDefinition($name){
		return $this->outputs[$name];
	}
	
	public function getOutputList(){
		return $this->outputs;
	}
	
	public function validateInputs(){
		$inputs = $this->getInputList();
		
		foreach($inputs as $inputName=>$inputCfg){
			/*if(!($inputCfg instanceof KernelDataPrimitiveFieldDefinition)){
				echo 'not a field def<br/>';
			}*/
			$typeObj = $inputCfg->getValue('Type');
			$requiredObj = $inputCfg->getValue('Required');
			$canBeListObj = $inputCfg->getValue('AllowList');
			$defaultValue = $inputCfg->getValue('DefaultValue');
			
			$type = $typeObj->getValue();
			$required = $requiredObj->getValue();
			$canBeList = $canBeListObj->getValue();
			
			if($defaultValue){
				$defaultValue = $defaultValue->getValue();
			}else{
				$defaultValue=null;
			}
			
			$inputValue = $this->getTaskInput($inputName);
			
			if($inputValue){
				if($canBeList){
					if($inputValue instanceof KernelDataPrimitiveList){
						
					}else{
						$this->addError($this->getClassName(), $inputName.' requires an Input with the Type of '.$type.'; A '.$inputValue->getClassName().' was supplied.');
					}
				}else{
					if($inputValue->getClassName()!=$type){
						$classNames = class_parents($inputValue);
						$typeFound = false;
						
						foreach($classNames as $className){
							$tObject = new $className();
							if($tObject){
								if($tObject->getClassName()==$type){
									$typeFound = true;
									break;
								}
							}
						}
						
						if(!$typeFound){
							$this->addError($this->getClassName(), $inputName.' requires an Input with the Type of '.$type.'; A '.$inputValue->getClassName().' was supplied.');
						}
					}	
				}
				
			}else{
				if($required){
					if($defaultValue!==null){
						$this->setTaskInput($inputName, DataClassLoader::createInstance($type, $defaultValue));
					}else{
						$this->addError($this->getClassName(), $inputName.' is a required Input');
					}
				}
			}
		}
		
		if($this->getErrorCount()>0){
			
			return false;
		}else{
			return true;
		}
	}
	
	public function getErrorCount(){
		$errors = $this->getTaskOutput('Errors');
		$count = $errors->getValue('count');
		//echo $count;
		return $count;
	}
	
	public function addError($className, $message, $errorNum=null){
		$this->setTaskOutput('ErrorOcurred', DataClassLoader::createInstance('Kernel.Data.Primitive.String', true));
		
		$errors = $this->getTaskOutput('Errors');
		
		$errorItem = DataClassLoader::createInstance('Kernel.Data.Primitive.Error');
		$errorItem->setValue('Class', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $className));
		$errorItem->setValue('Message', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $message));
		
		$errors->addItem($errorItem);
		$this->setTaskOutput('Errors', $errors);
	}
}
?>