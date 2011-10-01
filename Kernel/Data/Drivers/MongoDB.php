<?php
class KernelDataDriversMongoDB extends KernelDataDatabaseDriver{
	private $m;
	private $b;
	private $db;
	
	private $connected = false;
	
	public function __construct($config){
		parent::__construct($config);
		
		$this->_ClassName = 'Kernel.Data.Drivers.MongoDB';
		$this->_ClassTitle='MongoDB Database Driver';
		$this->_ClassDescription = 'A MongoDB Database driver for use with Flux Singularity Data Sources';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.4.0';
		
    	$this->connect($config);
    	
	}
    
	public function connect($config){
		$databaseName = $config['Database'];
		try{
			$m = new Mongo();
			$this->b = eval('$db = $m->$databaseName;');
			$this->m = $m;
			$this->db = $db;
			$this->connected = true;
			return true;
		}catch (Exception $e){
			$this->connected = false;
			return false;
		}
	}
	
	public function disconnect(){
		
	}
	
    public function loadById($collectionName, $id){
    	$ref = array(
			'$ref'=>$collectionName,
			'$id'=>new MongoId($id->getValue())
		);
		$db = $this->db;
		$item = $db->getDBRef($ref);
		$entity = $this->itemToEntity($item);
		return $entity;
    	//throw new Exception('Database Driver Must Implement loadById');
    }
    
    public function find($collectionName, $params){
    	$db = $this->db;
		$collection = $db->selectCollection($collectionName);
		$query = $this->paramsToQuery($params);
		$res = $collection->find($query);
		if($res){
			$return = $this->cursorToArray($res);
		}else{
			$return = false;
		}
		
		return $return;
    	//throw new Exception('Database Driver Must Implement find');
    }
    
    public function findOne($collectionName, $params){
    	
    	$db = $this->db;
		
		$collection = $db->selectCollection($collectionName);
		$query = $this->paramsToQuery($params);
		
		$res = $collection->findOne($query);
		
		
		if($res){
			$return = $this->itemToEntity($res, true);
		}else{
			$return = false;
		}
		
		return $return;
		
    	//throw new Exception('Database Driver Must Implement findOne');
    }
	
	public function cursorToArray($cursor){
		foreach ($cursor as $item) {
			print_r($item);
		}
	}
	
	public function itemToEntity($obj, $resolveRefs=false){
		$retObj = DataClassLoader::createInstance($obj['KernelClass']);
		$extendedFields = array();
		//handle extended classes first
		if($obj['KernelExtends'] && is_array($obj['KernelExtends']) && count($obj['KernelExtends'])>0){
			//load the extended item
			$db = $this->db;
			
			foreach($obj['KernelExtends'] as $parentClassName=>$parentRef){
				$parentData = $db->getDBRef($parentRef);
				$parentItem = DataClassLoader::createInstance($parentClassName, $parentData);
				$parentFields = $parentItem->getFields();
				
				foreach($parentFields as $parentFieldName=>$parentFieldValue){
					if(strpos($parentFieldName, 'Kernel')!==0){
						$parentVal = $parentItem->getValue($parentFieldName);
						$retObj->setValue($parentFieldName, $parentVal);
						$extendedFields[$parentFieldName] = true;
					}
				}
			}
		}
		
		$fields = $retObj->getFields();
		
		foreach($fields as $fieldName=>$fieldCfg){
			//echo 'Loading: '.$fieldName.'<br/>';
			if(!array_key_exists($fieldName, $extendedFields)){
				$objectValue = $obj[$fieldName];
				if($objectValue!==null){
					if($fieldCfg->getValue('AllowList')->getValue()==true){
						if(is_array($objectValue)){
							$list = DataClassLoader::createInstance('Kernel.Data.Primitive.List');
							foreach($objectValue as $index=>$subObj){
								if($subObj['$ref']){
									if($resolveRefs){
										$db = $this->db;
										$listItem = $db->getDBRef($subObj);
										$list->addItem($listItem);
									}else{
										//need some other solution to maintain data integrity
										echo 'find a solution for when not resolving refs netedly<br/>';
										echo 'suggestion: something to do with lazy loading of data';
									}
								}else{
									$list[] = DataClassLoader::createInstance($fieldCfg->getValue('Type')->getValue(), $subObj);
								}
							}
							$retObj->setValue($fieldName, $list);
						}
					}else{//no arrays
						if(is_array($objectValue) && $objectValue['$ref']!==null){
							$item = null;
							if($resolveRefs){
								$db = $this->db;
								$item = $db->getDBRef($objectValue);
							}else{
								echo 'find a solution for when not resolving refs netedly<br/>';
								echo 'suggestion: something to do with lazy loading of data';
							}
							$retObj->setValue($fieldName, $item);
						}else{
							$retObj->setValue($fieldName, DataClassLoader::createInstance($fieldCfg->getValue('Type')->getValue(), $objectValue));	
						}
							
					}	
				}	
			}
			
		}
		
		return $retObj;
	}
	
	public function itemToArray($obj, $resolveRefs=false){
		
		$obj['KernelID'] = $obj['_id'].'';
		unset($obj['_id']);
		//print_r($obj);
		$retObj = DataClassLoader::createInstance($obj['KernelClass']);
		$fields = $retObj->getFields();
		foreach($fields as $fieldName=>$fieldCfg){
			if($fieldCfg->getValue('AllowList')->getValue()){
				$list = DataClassLoader::createInstance('Kernel.Data.Primitive.List');
				foreach($obj[$fieldName] as $item){
					if($item instanceof MongoDBRef){
						echo 'its a ref<br/>';
					}else{
						
						echo 'its not a dbref<br/>';
					}	
				}
				
			}else{
				if($obj[$fieldName] instanceof MongoDBRef){
					echo 'its a ref<br/>';
				}else{
					$dataItem = DataClassLoader::createInstance($fieldCfg->getValue('Type')->getValue());
					$retObj->setValue($fieldName, $dataItem);
				}	
			}
			
		}
		
		return $retObj;
	}
    
    public function save($collection, $entity){
    	$object = $this->entityToObject($entity);
		$db = $this->db;
		$collection = $db->selectCollection($collection);
		$ret = $collection->save($object);
		
		$entity->setValue('KernelID', DataClassLoader::createInstance('Kernel.Data.Primitive.String',$object->_id.''));
		return $entity;
    	//throw new Exception('Database Driver Must Implement save');
    }
    
    public function remove(){
    	throw new Exception('Database Driver Must Implement remove');
    }
    
    public function getEntityReference($collection, $itemID){
    	$db = $this->db;
		$collection = $db->selectCollection($collection);
		$itemId = new MongoId($itemID->getValue());
		$item = $collection->findOne(array('_id'=>$itemId));
		$ref = $collection->createDBRef($item);
		return $ref;
    	//throw new Exception('Database Driver Must Implement getEntityReference');
    }
	
	private function paramsToQuery($params){
		if(!$params instanceof KernelDataPrimitiveConditionGroup){
			throw new Exception("Invalid Condition Group Supplied", 1);
		}
		
		$query = $this->conditionGroupToParamGroup($params);
		return $query;
	}
	
	private function conditionGroupToParamGroup($group){
			
		$params = array();
		$type = '$and'; //default
				
		switch($group->getValue('Type')->getValue()){
			case 'AND':
				$type = '$and';
				break;
			case 'OR':
				$type = '$or';
				break;
		}
		
		$conditions = $group->getConditions();
		
		$conditionCount = $conditions->Count();
		
		for($i=0;$i<$conditionCount;$i++){
			$conditionItem = $conditions->getItem($i);
			
			if($conditionItem instanceof KernelDataPrimitiveConditionGroup){
				$paramName = $conditionItem->getValue('Type')->getValue();
				$param = $this->conditionGroupToParamGroup($conditionItem);
			}else{
				if($conditionItem instanceof KernelDataPrimitiveCondition){
					$attribute = $conditionItem->getValue('Attribute');
					if($attribute){
						$paramName = $attribute->getValue();
						$param = $this->conditionToParam($conditionItem);
					}else{
						print_r($conditionItem);
						echo '<br/><br/>';
					}
					
				}else{
					throw new Exception("Invalid Condition Item", 1);
				}
			}
			$params[] = $param;
		}
		
		$paramGroup = array($type=>$params);
		return $paramGroup;
	}
	
	private function conditionToParam($condition){
		$operator = $condition->getValue('Operator')->getValue();
		$operator = DataNormalization::doComparisonOperator($operator);
		
		$attributeName = $condition->getValue('Attribute')->getValue();
		
		$attributeValue = $condition->getValue('Value')->getValue();
		
		switch($operator){
			case '==':
				$itemValue = $attributeValue;
				break;
			case '!=':
				$itemValue = array('$ne'=>$attributeValue);
				break;
			case '>':
				$itemValue = array('$gt'=>$attributeValue);
				break;
			case '>=':
				$itemValue = array('$gte'=>$attributeValue);
				break;
			case '<':
				$itemValue = array('$lt'=>$attributeValue);
				break;
			case '<=':
				$itemValue = array('$lte'=>$attributeValue);
				break; 
			case '@': // contains
				$itemValue = array('$regex'=>$attributeValue);
				break;
		}
		
		if($attributeName=='KernelID'){
			$attributeName = '_id';
			$itemValue = new MongoId($itemValue);
		}
		
		return array($attributeName=>$itemValue);
	}
	
	public function entityToObject($entity){
		
		$object = new stdClass();
		
		$fields = $entity->getFields();
		$extendedFields = array();
		
		if(count($entity->extends)>0){
			foreach($entity->extends as $extendedClassName=>$extendedClass){
				$extendedClassFields = $extendedClass->getFields();
				foreach($extendedClassFields as $extendedFieldName=>$extendedFieldCfg){
					if(strpos($extendedFieldName, 'Kernel')!==0){
						echo 'processing extend field: '. $extendedClass->getClassName().'::'.$extendedFieldName.'<br/>';
						$extendedClass->setValue($extendedFieldName, $entity->getValue($extendedFieldName));
						$extendedFields[$extendedFieldName] = true;
					}	
				}
				$object->KernelExtends[$extendedClassName] = $extendedClass->getEntityReference();
			}
		}
		
		foreach($fields as $fieldName=>$fieldCfg){
			$fieldValue = $entity->getValue($fieldName);
			if(!array_key_exists($fieldName, $extendedFields)){
				if($fieldName == 'KernelID'){
					if($fieldValue){
						$object->_id = new MongoId($fieldValue->getValue());	
					}
				}
				
				if($fieldValue){
					if(in_array('KernelDataEntity', class_parents($fieldValue))){
						$object->$fieldName = $fieldValue->getEntityReference();
					}else{
						if($fieldCfg->getValue('AllowList')->getValue()==true){
							$list = array();
							if($fieldValue instanceof KernelDataPrimitiveList){
								$itemCount = $fieldValue->Count();
								
								for($i=0;$i<$itemCount;$i++){
									$item = $fieldValue->getItem($i);
									if(in_array('KernelDataEntity', class_parents($item))){
										$list[] = $item->getEntityReference();
									}else{
										$list[] = $item->getValue();
									}
								}
							}
							$object->$fieldName = $list;
						}else{
							$object->$fieldName = $fieldValue->getValue();
						}
						
					}	
				}
			}
			
		}
		return $object;
	}
}