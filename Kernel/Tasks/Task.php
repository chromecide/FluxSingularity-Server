<?php 

/**
 * 
 * Base Object for all system tasks
 * @author justin.pradier
 *
 */
class KernelTasksTask extends KernelObject{
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
		
		
		//$this->setOutputValue('Completed', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', $completed));
		//$this->setOutputValue('ErrorOcurred', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', $errored));
		//$this->setOutputValue('Errors', DataClassLoader::createInstance('Kernel.Data.Primitive.List'));
	}
	
	public function run(){
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
	
	public function runTask(){
		return $this->run();
	}
	
	public function completeTask(){
		
		$completed = true;
		$errored = false;

		if($this->getErrorCount()>0){
			echo 'Errors: '.$this->getErrorCount().'<br/>';
			$completed = false;
			$errored = true;
		}
		
		$this->setOutputValue('Completed', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', $completed));
		$this->setOutputValue('ErrorOcurred', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', $errored));
		
		return true;
	}
	
	public function enableTask(){
		$this->setTaskInput('Enabled', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
	}
	
	public function isReady(){
		$enabled = $this->getTaskInput('Enabled');
		$completed = $this->getTaskOutput('Completed');
		$reset = $this->getTaskInput('Reset');
		//echo $this->getClassName().'<br/>';
		if(is_object($enabled) && $enabled->getValue()===true){
			if(!$completed || $completed->getValue()===false){
				return true;
			}else{
				if($reset && $reset->getValue()===true){
					return true;	
				}
			}
		}else{
			echo $this->getClassName().' not enabled<br/>';
		}	
		
		
		return false;
	}
	
	public function getInputDefinition($name){
		return $this->inputs[$name];
	}
	
	public function getOutputDefinition($name){
		return $this->outputs[$name];
	}
	
	public function setInputValue($name, $value){
		
		//retrieve the field definition
		$fieldDef = $this->getInputDefinition($name);
		
		$targetValue = null;
		
		//ensure the field definition exists
		if($fieldDef && ($fieldDef instanceof KernelDataPrimitiveTaskInput)){
			$typeObj = $fieldDef->getValue('Type');
			$requiredObj = $fieldDef->getValue('Required');
			$allowedObj = $fieldDef->getValue('AllowList');
			
			$type = $typeObj->getValue();
			$required = $requiredObj->getValue();
			$allowList = $allowedObj->getValue();
			
			//supplied as the appropriate type?
			if($value instanceof $type){
				if($required && $value->getValue()==null){
					$this->addError($this->getClassName(), 'Field Required: '.$name);
					return false;
				}else{
					if($allowList){
						$targetValue = $this->getInputValue($name);
						if(!($targetValue instanceof KernelDataPrimitiveList)){
							$targetValue = DataClassLoader::createInstance('Kernel.Data.Primitive.List');
						}
						$targetValue->addItem($value);
					}else{
						$targetValue = $value;	
					}
				}
			}else{
				if($required && $value==null){
					$this->addError($this->getClassName(), 'Field Required: '.$name);
					return false;
				}else{
					if($allowList){
						$targetValue = $this->getInputValue($name);
						if(!($targetValue instanceof KernelDataPrimitiveList)){
							$targetValue = DataClassLoader::createInstance('Kernel.Data.Primitive.List');
						}
						$targetValue->addItem($value);
						$targetValue = $value;
					}else{
						if($value instanceof KernelDataPrimitiveList){
							$this->addError($this->getClassName(), 'Field Does not allow List Values: '.$name);
							return false;
						}else{
							$targetValue = $value;
						}
					}
				}
			}
		}else{
			$this->addError($this->getClassName(), 'Invalid Input Definition Format: '.$name);
			return false;
		}
		
		$this->inputData[$name] = $targetValue;
	}
	
	public function getInputValue($name){
		if($name && array_key_exists($name, $this->inputData)){
			return $this->inputData[$name];	
		}else{
			return null;
		}
	}
	
	public function setOutputValue($name, $value){
		
		//retrieve the field definition
		$fieldDef = $this->getOutputDefinition($name);
		
		$targetValue = null;
		
		//ensure the field definition exists
		//print_r($fieldDef);
		
		if($fieldDef && ($fieldDef instanceof KernelDataPrimitiveTaskOutput)){
			if($name=='Completed'){
				//print_r($fieldDef);
			}
			$typeObj = $fieldDef->getValue('Type');
			$requiredObj = $fieldDef->getValue('Required');
			$allowedObj = $fieldDef->getValue('AllowList');
			
			$type = $typeObj->getValue();
			$required = $requiredObj->getValue();
			$allowList = $allowedObj->getValue();
			
			//supplied as the appropriate type?
			
			if($value && ($value->getClassName() == $type)){
				if($required && $value->getValue()==null){
					$this->addError($this->getClassName(), 'Output Field Required: '.$name);
					return false;
				}else{
					if($allowList){
						$targetValue = $this->getOutputValue($name);
						if(!($targetValue instanceof KernelDataPrimitiveList)){
							$targetValue = DataClassLoader::createInstance('Kernel.Data.Primitive.List');	
						}
						
						$targetValue->addItem($value);
					}else{
						$targetValue = $value;	
					}
				}
			}else{
				if($required && $value==null){
					$this->addError($this->getClassName(), 'Output Field Required: '.$name);
					return false;
				}else{
					if($allowList){
						 $targetValue = $this->getOutputValue($name);
						if(!($targetValue instanceof KernelDataPrimitiveList)){
							$targetValue = DataClassLoader::createInstance('Kernel.Data.Primitive.List');	
						}
						
						$targetValue->addItem($value);
						
					}else{
						if($value instanceof KernelDataPrimitiveList){
							$this->addError($this->getClassName(), 'Output Field Does not allow List Values: '.$name);
							return false;
						}else{
							echo $name.'<br/>';
							echo $type.':'.$value->getClassName().'<Br/>';
							$targetValue = DataClassLoader::createInstance($type, $value);	
						}
					}
				}
			}
		}else{
			
			$this->addError($this->getClassName(), 'Invalid Output Definition Format: '.$name);
			return false;
		}
		
		$this->outputData[$name] = $targetValue;
	}
	
	public function getOutputValue($name){
		if($name && array_key_exists($name,$this->outputData)){
			return $this->outputData[$name];
		}else{
			return null;
		}
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
		if(array_key_exists($input, $this->inputData)){
			return $this->inputData[$input];	
		}else{
			return null;
		}
		
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
	
	public function getInputList(){
		return $this->inputs;
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
		if($errors instanceof KernelDataPrimitiveList){
			$count = $errors->getValue('count');	
		}else{
			$count = 0;
		}
		
		//echo $count;
		return $count;
	}
	
	public function addError($className, $message, $errorNum=null){
		echo 'setting error value '.$className.'<Br/>';
		print_r($message);
				die();
		$this->setOutputValue('ErrorOcurred', DataClassLoader::createInstance('Kernel.Data.Primitive.String', true));
		
		$errors = $this->getOutputValue('Errors');
		
		$errorItem = DataClassLoader::createInstance('Kernel.Data.Primitive.Error');
		$errorItem->setValue('Class', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $className));
		$errorItem->setValue('Message', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $message));
		
		$errors->addItem($errorItem);
		$this->setOutputValue('Errors', $errors);
	}
}
?>