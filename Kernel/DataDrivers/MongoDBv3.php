<?php

class KernelDataDriverMongoDBv3 extends KernelDataDriver {
	private $mongo;
	private $db;
	private $config=array();
	protected static $objectCache = array();
	
	function __construct($config){
		parent::__construct($config);
		$this->config = $config;
	}
	
	public function connect(){
		$this->mongo = new Mongo();
		
		if(array_key_exists('Database', $this->config)){
			$databaseName = $this->getDatabaseName();
			$mongo = $this->mongo;
			$this->db = $mongo->$databaseName;
			return true;
		}
		
		return false;
	}
	
	public function disconnect(){
		unset($this->db);
		unset($this->mongo);
		return true;
	}
	
    public function &findById($objectId){
		$collectionName = $this->getObjectCollectionName();
		
    	$db = $this->db;
		$collection = $db->$collectionName;
    	$mongoQuery = array();
		
    	if($objectId){
    		if(array_key_exists($objectId, self::$objectCache)){
    			$returnObject = self::$objectCache[$objectId];
				return $returnObject;
    		}else{
    			$mongoQuery['Data.ID'] = $objectId;
			
				$cursor = $collection->findOne($mongoQuery);
				if($cursor){
					self::$objectCache[$objectId] = $cursor;
					$cursorRef = $cursor;
					return $cursorRef;
				}	
    		}
    		
    	}else{
    		throw new Exception("Invalid Query Object (findById)", 1);
    	}
		$returnVal = false;
		return $returnVal;
    }
    /*
	 * >
	 * >=
	 * <
	 * <=
	 * ==
	 * !=
	 * IN
	 * NOT IN
	 * REGEX (DEAL WITH THIS LATER)
	 * Exists
	 * 
	 * OR
	 * AND
	 * 
	 */
    public function find(&$queryObject){
    	
    	$returnArray = array();
		$collectionName = $this->getObjectCollectionName();
		
    	$db = $this->db;
		$collection = $db->$collectionName;
    	$mongoQuery = array();
    	if($queryObject instanceof KernelObject){
    		if($queryObject->usesDefinition('Object.Query')){
				$fullQuery = $this->objectToQuery($queryObject);
    		}else{
    			//$queryObject->addField('Results', 'Kernel.Object', false, true);
    			//basic object based query
    			$queryModel = $queryObject->getModel();
				$queryObject->returnDefaults = false;
				
				$definitions = $queryModel['Definitions'];
				if(count($definitions)>0){
					$mongoQuery['Definitions']=array();
					foreach($definitions as $definitionID){
						$mongoQuery['Definitions'] = $definitionID;
					}	
				}
				
				foreach($queryAttributes as $attributeName=>$attributeCfg){
					$attributeValue = $queryObject->getValue($attributeName);
					$definitions = array();
					$required = false; 
					$isList = false;
					$isPrimitive=false;
					
					if($attributeCfg){
						if(array_key_exists('Definitions', $attributeCfg)){
							$definitions = $attributeCfg['Definitions'];
						}
						
						if(array_key_exists('Required', $attributeCfg)){
							$required = $attributeCfg['Required'];
						}
						
						if(array_key_exists('IsList', $attributeCfg)){
							$isList = $attributeCfg['IsList'];
						}
						
						if(array_key_exists('IsPrimitive', $attributeCfg)){
							$isPrimitive = $attributeCfg['IsPrimitive'];
						}
					}
					$queryObject->returnDefaults = false;
					$attributeValue = $queryObject->getValue($attributeName);
					
					if(!is_null($attributeValue)){
						if($attributeValue instanceof KernelObject){
							if($isPrimitive){
								$mongoQuery['Data.'.$attributeName] = $attributeValue->getModel();
							}else{
								$mongoQuery['Data.'.$attributeName] = array('ID'=>$attributeValue->getValue('ID'));
							}
						}else{
							$mongoQuery['Data.'.$attributeName] = $attributeValue;
						}
					}
				}

				if(count($mongoQuery)==0){
					$fullQuery = array();
				}else{
					$fullQuery = array('$and'=>array($mongoQuery));	
				}
				
    		}
    		
			if($queryObject->getValue('_QuerySort')){
				$sortFieldName = $queryObject->getValue('_QuerySort');
			}else{
				$sortFieldName = 'Definitions';
			}
			
			$cursor = $collection->find($fullQuery)->sort(array($sortFieldName=>0));
			
			if($cursor){
				while( $cursor->hasNext() ) {
					$returnArray[] = $cursor->getNext();
				}	
			}
			
    	}else{
    		throw new Exception("Invalid Query Object (find)", 1);
    	}
		if(!$returnArray){
			$returnArray = array();
		}
		
		//$queryObject->setValue('QueryString', json_encode($fullQuery));
		//$queryObject->setValue('Results', $returnArray);
		
		return $returnArray;
    }
    
    public function findOne(&$queryObject){
    	
    	$returnArray = array();
		$collectionName = $this->getObjectCollectionName();
		
    	$db = $this->db;
		$collection = $db->$collectionName;
    	$mongoQuery = array();
    	if($queryObject instanceof KernelObject){
    		//load the field definitions
    		$queryFields = $queryObject->getAttributes();
			if($queryObject->usesDefinition('Object.Query')){
				
			}else{
				$attributeList = $queryObject->getAttributes();
				foreach($attributeList as $attributeName=>$attributeCfg){
					$definitions = array();
					$required = false; 
					$isList = false;
					$isPrimitive=false;
					
					if($attributeCfg){
						if(array_key_exists('Definitions', $attributeCfg)){
							$definitions = $attributeCfg['Definitions'];
						}
						
						if(array_key_exists('Required', $attributeCfg)){
							$required = $attributeCfg['Required'];
						}
						
						if(array_key_exists('IsList', $attributeCfg)){
							$isList = $attributeCfg['IsList'];
						}
						
						if(array_key_exists('IsPrimitive', $attributeCfg)){
							$isPrimitive = $attributeCfg['IsPrimitive'];
						}
					}
					$queryObject->returnDefaults = false;
					$attributeValue = $queryObject->getValue($attributeName);
					
					if(!is_null($attributeValue)){
						if($attributeValue instanceof KernelObject){
							if($isPrimitive){
								$mongoQuery['Data.'.$attributeName] = $attributeValue->toArray();
							}else{
								$mongoQuery['Data.'.$attributeName] = array('ID'=>$attributeValue->getValue('ID'));
							}
						}else{
							$mongoQuery['Data.'.$attributeName] = $attributeValue;
						}
					}
				}
			}
			
			if($queryObject->getValue('SortBy')){
				$sortFieldName = $queryObject->getValue('SortBy');
			}else{
				$sortFieldName = 'ID';
			}
			
			$resultObject = $collection->findOne($mongoQuery);
			
			if($resultObject){
				//$finalResult = $this->populateObject(&$queryObject, $resultObject);
				return $resultObject;
				return true;
			}else{
				return false;
			}
			
    	}else{
    		fb($queryObject);
			
    	}
    }

    public function save(&$model){
    	$collectionName = $this->getObjectCollectionName();
			
		$db = $this->db;
		$collection = $db->$collectionName;
		
		
		//$existingDocument = $collection->findOne($mongoQuery);
		
		if($collection->save(&$model)){
			$itemId = false;
			
			if(array_key_exists('ID', $model['Data'])){
				$itemId = $model['Data']['ID'];	
			}
			
			if(array_key_exists('Definitions', $model)){
				$itemId = $model['Data']['ID'];	
			}
			
			if(!$itemId){
				$itemId = $model['_id'].'';
				$model['Data']['ID']=$itemId;
				$collection->save(&$model);
			}
			
			//if(!array_key_exists($model['Data']['ID'], self::$objectCache)){
				self::$objectCache[$model['Data']['ID']]=&$model;
			/*}else{
				self::$objectCache[$model['Data']['ID']]=&$model;
			}*/
			return true;
		}else{
			return false;
		}
    }
	
    public function remove(&$object){
    	$collectionName = $this->getObjectCollectionName();
		
    	$db = $this->db;
		$collection = $db->$collectionName;
    	$mongoQuery = array();
		$queryObject = new KernelObject(array('SuspendEvents'=>true));
		
    	if($object->getValue('ID')){
    		$conditions = array('Data.ID'=>$object->getValue('ID'));
			return $collection->remove($conditions);
    	}else{
    		$object->addError('Object Not Found');
			return false;
    		//throw new Exception("Invalid Query Object (remove)", 1);
    	}
    }
    
    public function getEntityReference(){
    	throw new Exception('Database Driver Must Implement getEntityReference');
    }
	
	protected function getDatabaseName(){
		$databaseName = 'FluxSingularity_Data';
		
		if(array_key_exists('Database', $this->config)){
			$databaseName = $this->config['Database'];
		}
		
		return $databaseName;	
	}
	
	protected function getObjectCollectionName(){
		$collectionName = 'DataObjects';
		
		if(array_key_exists('ObjectCollection', $this->config)){
			//$collectionName = $this->config['ObjectCollection'];	
		}
		
		return $collectionName;
	}
	
	protected function getDefinitionCollectionName(){
		$collectionName = 'DataDefinitions';
		
		if(array_key_exists('DefinitionCollection', $this->config)){
			$collectionName = $this->config['DefinitionColleciton'];	
		}
		
		return $collectionName;
	}
	
	protected function getModuleCollectionName(){
		$collectionName = 'ModuleDefinitions';
		
		if(array_key_exists('ModuleCollection', $this->config)){
			$collectionName = $this->config['ModuleCollection'];	
		}
		
		return $collectionName;
	}
	
	public function isConnected(){
		$connected = false;
		if($this->mongo){
			$connected = $this->mongo->connected;
		}
		return $connected;
	}
	
	public function documentToObject($document){
		
		$object = new KernelObject(array('SuspendEvents'=>true));
		if(array_key_exists('_id', $document)){
			$object->setObjectId($document['_id'].'');
		}
		
		if(array_key_exists('Name', $document)){
			$object->setObjectName($document['Name']);
		}

		if(array_key_exists('Description', $document)){
			$object->setObjectDescription($document['Description']);
		}
		
		if(array_key_exists('Author', $document)){
			$object->setObjectAuthor($document['Author']);
		}
		
		if(array_key_exists('Version', $document)){
			$object->setObjectVersion($document['Version']);
		}
		
		if(array_key_exists('Definitions', $document)){
			foreach($document['Definitions'] as $definitionName){
				if($definitionName!='Kernel.Object'){
					$object->addDefinition($definitionName);
				}	
			}
		}
		
		if(array_key_exists('Fields', $document)){
			foreach($document['Fields'] as $fieldCfg){
				$field = new KernelObjectFieldDefinition($fieldCfg);
				$object->addField($field);
			}
		}
		
		if(array_key_exists('Data', $document)){
			$data = $document['Data'];
			if(is_array($data)){
				foreach($data as $dataName=>$dataValue){
					$object->setValue($dataName, $dataValue);
				}		
			}else{
				$object->setValue($data);
			}
		}
		return $object;
	}

	public function populateObject(&$object, $document){
		if($document['_id']){
			$document['ID'] = $document['_id'].'';
			unset($document['_id']);
		}
		
		$object->setModel($document, true);
		return true;
	}
	
	private function objectToQuery($queryObject){
		$mongoQuery = false;
		if($queryObject instanceof KernelObject){
    		if($queryObject->usesDefinition('Object.Query')){
    			$mongoQuery = array();
				$conditions = $queryObject->getValue('Conditions');
				
				$conditionList = array();
				
				foreach($conditions as $conditionCfg){
					$condition = new KernelObject();
					$condition->setModel($conditionCfg);
					if($condition->usesDefinition('Object.Query')){
						$conditionConfig = $this->objectToQuery($condition);
					}else{
						$conditionConfig = array();
						$conditionFieldName = 'Data.'.$condition->getValue('AttributeName');
						$conditionOperator = $condition->getValue('Operator');
						$conditionValue = $condition->getValue('Value');
						$conditionItem = array();
						
						if($conditionValue instanceof KernelObject){
							$conditionValue = $conditionValue->getValue('ID');
							$conditionFieldName .= '.ID'; 
						}else{
							if(is_array($conditionValue)){
								$idAdded = false;
								if(count($conditionValue)==1){
									$tempConditionValues = $conditionValue[0];
								}else{
									foreach($conditionValue as $conditionValueItem){
										$tempConditionValues = array();
										if($conditionValueItem instanceof KernelObject){
											if(!$idAdded){
												$idAdded = true;
												$conditionFieldName .= '.ID';
											}
											$tempConditionValues[] = $conditionValueItem->getValue('ID');
										}else{
											$tempConditionValues[] = $conditionValueItem;
										}
									}	
								}
								
								$conditionValue = $tempConditionValues;
							}
						}
						if($conditionValue=='[]'){
							$conditionValue = array();
						}
						switch($conditionOperator){
							case '>':
								$conditionItem[$conditionFieldName]=array('$gt'=>$conditionValue);
								break;
							case '>=':
								$conditionItem[$conditionFieldName]=array('$gte'=>$conditionValue);
								break;
							case '<':
								$conditionItem[$conditionFieldName]=array('$lt'=>$conditionValue);
								break;
							case '<=':
								$conditionItem[$conditionFieldName]=array('$lte'=>$conditionValue);
								break;
							case '!=':
								$conditionItem[$conditionFieldName]=array('$ne'=>$conditionValue);
								break;
							case 'IN':
								if(!is_array($conditionValue)){
									$conditionValue = array($conditionValue);
								}
								$conditionItem[$conditionFieldName]=array('$in'=>$conditionValue);
								break;
							case 'NIN':
								$conditionItem[$conditionFieldName]=array('$nin'=>$conditionValue);
								break;
							case 'EXISTS':
								$conditionItem[$conditionFieldName]=array('$exists'=>$conditionValue);
								break;
							case '==':
							default:
								$conditionItem[$conditionFieldName]=$conditionValue;
								break;
						}
						$conditionConfig = $conditionItem;
					}
					$conditionList[]=$conditionConfig;
				}

				if(count($conditionList)>0){
					if($queryObject->getValue('QueryType')=='OR'){
						$mongoQuery['$or'] = $conditionList;
					}else{
						$mongoQuery['$and'] = $conditionList;
					}
				}else{
					$mongoQuery = $conditionList;
				}
			}
		}
		
		return $mongoQuery;
	}
}
?>
