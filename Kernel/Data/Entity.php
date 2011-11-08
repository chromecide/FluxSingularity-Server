<?php 
class KernelDataEntity extends KernelData{
	public static $kernelStore=null;
	public $fields = array();
	public $changedFields = array();
	
	public $extends = array();
	
	public $connector=null;
	
	public $store = null;
	
	public $collectionName='Kernel.Entities';
	
	public $collectionObj = null;
	public $mongoObj = null;
	public $original = null;
	public $cursor=null;
	
	public $validationErrors = array();
	
	public function getData(){
		return $this->data;
	}
	
	public function __construct($data){
		parent::__construct($data);
		
		$this->_ClassName = 'Kernel.Data.Entity';
		$this->_ClassTitle='Kernel Entity Base Object';
		$this->_ClassDescription = 'Entity Objects are Data Objects that, when saved, require a Unique ID.';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.4.0';
		
		$this->data = array();
		
		$this->fields['KernelID'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Kernel ID', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>false, 'AllowList'=>false));
		$this->fields['KernelClass'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Kernel Class', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true));
		$this->fields['KernelName'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'KernelName', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>false));
		$this->fields['KernelDescription'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'KernelDescription', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>false));
		$this->fields['KernelRevision'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'KernelRevision', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>false));
		$this->fields['KernelCreatedBy'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'KernelCreatedBy', 'Type'=>'Kernel.Data.Security.User', 'Required'=>false));
		$this->fields['KernelCreatedDate'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'KernelCreatedAt', 'Type'=>'Kernel.Data.Primitive.DateTime', 'Required'=>false));
		$this->fields['KernelModifiedBy'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'KernelModifiedBy', 'Type'=>'Kernel.Data.Security.User', 'Required'=>false));
		$this->fields['KernelModifiedDate'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'KernelModifiedAt', 'Type'=>'Kernel.Data.Primitive.DateTime', 'Required'=>false));
		
		if(is_array($data) && array_key_exists('extends', $data)){
			foreach($data['extends'] as $extend){
				$this->extendClass($extend);	
			}	
		}else{
			if(is_object($data) && $data->extends){
				foreach($data->extends as $extend){
					$this->extendClass($extend);	
				}	
			}	
		}

		if(self::$kernelStore){
			$this->store = self::$kernelStore;
		}
	}
	
	
	function loadData($data){
		if(!is_array($data)){
			$data = array();	
		}
		
		if(array_key_exists('KernelID', $data)){
			$this->setValue('KernelID', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $data['KernelID']));
		}

		if(array_key_exists('KernelClass', $data)){
			$this->setValue('KernelClass', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $data['KernelClass']));
		}else{
			$this->setValue('KernelClass', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $this->getClassName()));
		}

		if(array_key_exists('KernelName', $data)){
			$this->setValue('KernelName', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $data['KernelName']));
		}

		if(array_key_exists('KernelDescription', $data)){
			$this->setValue('KernelDescription', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $data['KernelDescription']));
		}

		if(array_key_exists('KernelRevision', $data)){
			$this->setValue('KernelRevision', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $data['KernelRevision']));
		}
		
		if(array_key_exists('Store', $data)){
			$this->store = $data['Store'];
		}
		$fields = $this->getFields();
		
		foreach($fields as $fieldName=>$fieldCfg){
			
<<<<<<< HEAD
			if($data[$fieldName]){
=======
			if(array_key_exists($fieldName, $data)){
>>>>>>> Development-Main
				if($fieldCfg instanceof KernelDataPrimitiveFieldDefinition){
					$type = $fieldCfg->getValue('Type')->getValue();
					$allowList = $fieldCfg->getValue('AllowList')->getValue();
					
					$className = str_replace('.', '', $type);
					
					if($data[$fieldName] instanceof $className){
						$this->setValue($fieldName, $data[$fieldName]);
					}else{
						if($allowList){
							if($data[$fieldName] instanceof KernelDataPrimitiveList){
								$this->setValue($fieldName, $data[$fieldName]);
							}else{
								$value = DataClassLoader::createInstance('Kernel.Data.Primitive.List');
								if(is_array($data[$fieldName])){
									foreach($data[$fieldName] as $item){
										if($item instanceof $type){
											$value->addItem($item);
										}else{
											$value->addItem(DataClassLoader::createInstance($type, $item));
										}
									}
								}else{
									$value->addItem(DataClassLoader::createInstance($type, $data[$fieldName]));
								}
							}
						}else{
							
							$this->setValue($fieldName, DataClassLoader::createInstance($type, $data[$fieldName]));	
						}
					}
				}else{
					echo $this->getClassName().' - Invalid Field Definition: '.$fieldName;
					echo '<br/><br/><Br/>';
				}	
			}
			//$fieldCfg->getValue('Type')->getValue().'<br/>';	
		}
	}
	
	public function extendClass($className){
		//echo 'extending: '.$className.'<br/>';
		$this->extends[$className] = false;
		$class = DataClassLoader::createInstance($className);
		
		if($class){
			$classFields = $class->getFields();
			foreach($classFields as $fieldName=>$fieldCfg){
				$this->fields[$fieldName] = $fieldCfg;
			}
			$this->extends[$className] = $class;
		}
	}
	/*
	 * Entity Getter and Setter Functions
	 */
	function setValue($fieldName, $value){
		$currentValue = null;
		if(array_key_exists($fieldName, $this->data)){
			$currentValue = $this->data[$fieldName];	
		}
		
		if($value!=$currentValue){
			$this->changedFields[] = $fieldName;
			$this->data[$fieldName] = $value;	
		}
	}
	
	function getValue($fieldName=null, $defaultValue=null){
		if($fieldName===null){
			return $this->toBasicObject();
		}else{
			if(array_key_exists($fieldName, $this->data)){
				$value = $this->data[$fieldName];	
				if(in_array('KernelData', class_parents($value))){
					$retValue = $value;
				}else{
					$retValue = $defaultValue;
				}	
			}else{
				return null;
			}
		}
		
		
		return $retValue;
	}
	
	public function setStore($store){
		$this->store = $store;	
	}
	
	public function getStore(){
		return $this->store;
	}
	/*
	 * End Getter and Setter Functions
	 */
	
	/*
	 * Entity Database Objects
	 */
	
	/**
	 * 
	 * Load an Entity using a System ID
	 * @param $id
	 * @param $fields
	 */
	function loadById($id, $fields=null){
		if($id===null){
			$id = $this->get('KernelID');
		}
		
		if(!$id instanceof KernelDataPrimitiveString){
			$id = DataClassLoader::createInstance('Kernel.Data.Primitive.String', $id);
		}
		
		$params = array(
			'Type'=>'AND',
			'Conditions'=>array(
				array(
					'Attribute'=>'KernelID',
					'Operator'=>'=',
					'Value'=>$id
				)	
			)
		);
		
		$conditionGroup = DataClassLoader::createInstance('Kernel.Data.Primitive.ConditionGroup', $params);
		
		
		$collection = $this->collectionName;
		
		$result = $this->store->findOne($this, $conditionGroup);
		
		if($result){
			$fields = $this->getFields();
			foreach($fields as $fieldName=>$fv){
				$this->setValue($fieldName, $result->getValue($fieldName));
			}
			return true;
		}else{			
			return false;
		}
	}
	
	/**
	 * 
	 * Search the Entity Collection.  Returns a Kernel.Data.Primitive.List containing the Entities found that match the supplied parameters
	 * @param $params
	 */
	function find($params){
		$data = $this->store->find($this->collectionName, $params);
		
		return false;
		$return = DataClassLoader::createInstance('Kernel.Data.Primitive.List');
		
		$return->setType($this->getClassName());
			
		foreach($data as $record){
			$return->addItem($record);
		}
		
		return $return;
	}
	
	/**
	 * 
	 * Search the Entity Collection and return the first Entity found that matches the supplied parameters
	 * @param unknown_type $params
	 */
	function findOne($params){		
		$return = $this->store->findOne($this->collectionName, $params);
		
		return $return;
	}
	
	public function findAsArray($params){
		if(!$this->find($params)){
			return false;
		}else{
			$ret = $this->toArray();
			return $ret;
		}
	}
	
	/**
	 * 
	 * Save the current Entity to the database
	 */
	function save(){
		if(!$this->beforeSave()){
			return false;
		}
		
		if(!$this->validate()){
			return false;
		}
		
		$kernelVersion = $this->getValue('KernelVersion');
		
		if($kernelVersion){
			$this->setValue('KernelRevision', DataClassLoader::createInstance('Kernel.Data.Primitive.String', $this->incrementRevision($kernelVersion->getValue())));
		}else{
			$this->setValue('KernelRevision', DataClassLoader::createInstance('Kernel.Data.Primitive.String', '1.0.0'));
		}
		
		$return = $this->store->save($this);
		
		$this->afterSave();
		
		return $return;
	}
	
	/**
	 * 
	 * Remove the current Entity from the Database
	 */
	function remove(){
		$thisIdObj = $this->get('KernelID');
		if($thisIdObj && $thisIdObj instanceof KernelDataPrimitiveString){
			$this->store->remove($this->collectionName, array('KernelID'=>$thisIdObj));
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 
	 * Return an Entity Reference for Nested Entity Data
	 */
	function getEntityReference(){
		$kernelID = $this->getValue('KernelID');
		if(!$kernelID || !$kernelID->getValue()){
			$this->save();
			$kernelID = $this->getValue('KernelID');
			
		}
		//echo $this->collectionName.'<br/>';
		$ref =  $this->store->getEntityReference($this);
		//echo 'here';
		return $ref;
	}
	
	/*
	 * End Entity database Functions
	 */
	
	/*
	 * Utility Functions
	 */
	function getFields(){
		return $this->fields;
	}
	
	function toBasicObject(){
		$ret = new stdClass();
		$ret->KernelClass = $this->getClassName();
		
		foreach($this->fields as $field=>$fieldDef){
			$type = $fieldDef->getItem('Type')->getValue();
			$required = $fieldDef->getItem('Required')->getValue();
			$isList = $fieldDef->getItem('AllowList')->getValue();
			$defaultValue = $fieldDef->getItem('DefaultValue');
			
			$fieldType = $type;
			//echo $fieldType.'<br/>';
			$valueObj = $this->getValue($field);
			
			if($valueObj){
				switch($fieldType){
					case 'Kernel.Data.Primitive.List':
					case 'Kernel.Data.Primitive.NamedList':
						$value = $valueObj->toBasicObject();
						break;
					default:
						$parentClasses = class_parents($valueObj);
						//if it's a primitive type, just call the get function
						if(in_array('KernelDataPrimitive', $parentClasses)){
							if($valueObj instanceof KernelDataPrimitiveList){
								$value = $valueObj->toBasicObject();
							}else{
								$value = $valueObj->getValue();	
							}
						}else{
							
							//if it's an entity type call it's corresponding toJSON function
							if(in_array('KernelDataEntity', $parentClasses)){
								
								$value = $valueObj->toBasicObject();
							}
						}
						
						break;
				}
			}else{
				$value = '';
			}
			$ret->$field = $value;
		}
		
		return $ret;
	}
	
	function toJSON(){
		$ret = $this->toBasicObject();
		return json_encode($ret);
	}
	
	function toArray(){
		if ($this->cursor){
			$ret = array();
			foreach($this->cursor as $mongoObj){
				$className = DataClassLoader::loadClass($mongoObj['KernelClass']);
				
				$tItem = new $className($mongoObj);
				$ret[] = $tItem->toArray();
			}
		}elseif($this->mongoObj){
			$ret = $this->mongoObj;
			$itemId = $ret['_id'];
			$ret['_id'] = (string)$itemId;
		}
		return $ret;
	}
	
	
	/**
	 * Override functions
	 */
	
	public function incrementRevision($value){
		if($value=='' || $value == null){
			$value = '1.0.0';
		}
		
		$parts = explode('.', $value);
		
		$majorRev = $parts[0];
		$minorRev = $parts[1];
		$revRev = $parts[2];
		
		//TODO: do something funky to determine how much has changed since last version
		//in the mean time, we are gonna have HUGE revRev Numbers :-(
		$revRev++;
		
		$ret = implode('.', array($majorRev, $minorRev, $revRev));
		
		return $ret;
		
	}
	
	public function validate(){
		$this->validationErrors = array();
		foreach($this->fields as $field=>$fieldDef){
			$valid = true;
			$type = $fieldDef->getItem('Type')->getValue();
			$required = $fieldDef->getItem('Required')->getValue();
			$isList = $fieldDef->getItem('AllowList')->getValue();
			$defaultValue = $fieldDef->getItem('DefaultValue');
			
			$fieldType = str_replace('.', '', $type);
			
			$fieldValue = $this->getValue($field);
			
			if($required && $fieldValue===null){ //if required and no value supplied
				
				if($defaultValue!==null){
					$this->setValue($field, $defaultValue);
				}else{
					$this->validationErrors[] = array('FieldRequired', $field);
					$valid = false;
				}
			}else{ //we have a value, ensure it's of the correct type
				
				//if it's a list, ensure the value is a list
				if($isList){
					if($required){
						if(!$fieldValue instanceof KernelDataPrimitiveList){
							//Not a valid list object
							$this->validationErrors[] = array('InvalidType', $field.' expects list');
							$valid = false;
						}else{
							//A valid list object was found
							//ensure the list is set to the correct type
							if(!$fieldValue->getType()==$fieldType){
								//wrong list entity type
								$this->validationErrors[] = array('InvalidType', $field.' expects list of type: '.$fieldType);
								$valid = false;
							}else{
								//list is the right type
								//if required, ensure the list has at least 1 item
								if($required && $fieldValue->Count()<1){
									$this->validationErrors[] = array('InvalidValue', $field.' expects at least one list item');
									$valid = false;
								}
							}
						}	
					}else{
						$valid = true;
					}
				}else{
					//not a list, ensure the value is of the right type
					if($required){
						if(!$fieldValue instanceof $fieldType){
							$this->validationErrors[] = array('InvalidType', $field);
							$valid = false;
						}else{
							//right type
							//ensure the data item actually has a value set
							
							if(!in_array('KernelData', class_parents($fieldValue))){
								if($defaultValue!==null){
									echo 'default value not null<br/>';
									$this->setValue($field, $defaultValue);
								}else{
									$this->validationErrors[] = array('FieldRequired', $field);
									$valid = false;
								}
							}
						}	
					}
				}
			}
			
			//echo '&nbsp;&nbsp;Valid:';
			if(!$valid){
				$this->validationErrors[] = array('InvalidValue', $field);
				//echo 'FAILED<br/><br/>';
			}else{
				//echo 'PASSED<br/><br/>';
			}
		}
		
		if(count($this->validationErrors)>0){
			return false;
		}else{
			return true;	
		}
	}
	
	
	/*
	 * Class Event Functions
	 */
	
	public function beforeSave(){
		return true;
	}
	
	public function afterSave(){
		return true;
	}
	
	/*
	 * End Class Event Functions
	 */
}
?>