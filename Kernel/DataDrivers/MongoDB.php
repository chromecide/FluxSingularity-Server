<?php

class KernelDataDriverMongoDB extends KernelDataDriver {
	private $mongo;
	private $db;
	private $config=array();
	
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
	
    public function loadById(&$object){
    	
		$collectionName = $this->getObjectCollectionName();
		
    	$db = $this->db;
		$collection = $db->$collectionName;
    	$mongoQuery = array();
		//$queryObject = new KernelObject('Object.Query');
		
    	if($object->getValue('ID')){
    		$mongoQuery['Data.ID'] = $object->getValue('ID');
			
			$cursor = $collection->findOne($mongoQuery);
			
			if($cursor){
				return $this->populateObject($object, $cursor);
			}
			
    	}else{
    		throw new Exception("Invalid Query Object", 1);
    	}
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
				$conditions = $queryObject->getValue('Conditions');
				
				if(is_array($conditions) && count($conditions)>0){
					foreach($conditions as $condition){
						
						$conditionItem = array();
						$conditionFieldName = $condition->getValue('Attribute');
						$conditionOperator = $condition->getValue('Operator');
						$conditionValue = $condition->getValue('Value');
						
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
								$conditionItem[$conditionFieldName]=array('$exists'=>true);
								break;
							case '==':
							default:
								$conditionItem[$conditionFieldName]=$conditionValue;
								break;
						}
						$mongoQuery[] = $conditionItem;
					}
				}
				
				if($queryObject->getValue('Type')=='AND'){
					if(count($mongoQuery)>0){
						$fullQuery = array('$and'=>$mongoQuery);	
					}else{
						$fullQuery = $mongoQuery;
					}
				}else{
					if(count($mongoQuery)>0){
						$fullQuery = array('$and'=>$mongoQuery);	
					}else{
						$fullQuery = $mongoQuery;
					}
				}
				
    		}else{
    			//$queryObject->addField('Results', 'Kernel.Object', false, true);
    			//basic object based query
	    		$queryFields = $queryObject->getObjectFields();
				
				foreach($queryFields as $field){
					switch($field->getName()){
						case '_QueryType':
						case '_QuerySort':
							break;
						case '_id':
							$mongoQuery[$field->getName()] = new MongoId($queryObject->getValue($field->getName()));
							break;
						case 'Name':
						case 'Author':
						case 'Description':
						case 'Version':
						case 'Definitions':
						case 'Fields':
							$mongoQuery[$field->getName()] = $queryObject->getValue($field->getName());
							break;
						default:
							$mongoQuery['Data.'.$field->getName()] = $queryObject->getValue($field->getName());
					}
				}
				$fullQuery = array('$and'=>array($mongoQuery));
    		}
    		
			if($queryObject->getValue('_QuerySort')){
				$sortFieldName = $queryObject->getValue('_QuerySort');
			}else{
				$sortFieldName = '_id';
			}
			
			//echo json_encode($fullQuery);
			//echo '<br/><Br/>';
			
			$cursor = $collection->find($fullQuery)->sort(array($sortFieldName=>0));
			
			if($cursor){
				while( $cursor->hasNext() ) {
					$resultObject = new KernelObject();
					$this->populateObject($resultObject, $cursor->getNext());
					$returnArray[] = $resultObject;
				}	
			}
			
    	}else{
    		throw new Exception("Invalid Query Object", 1);
    	}
		if(!$returnArray){
			$returnArray = array();
		}
		$queryObject->setValue('Results', $returnArray);
		
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
				$finalResult = $this->populateObject($queryObject, $resultObject);
				return true;
			}else{
				return false;
			}
			
    	}else{
    		throw new Exception("Invalid Query Object", 1);
    	}
    }
    
    public function save(&$object){
    	if($object instanceof KernelObject){
    		$collectionName = $this->getObjectCollectionName();
			
			$db = $this->db;
			$collection = $db->$collectionName;
			
			$objectId = $object->getValue('ID');
			$objectData = $object->toArray(true, true, true, true, true, false, false);
			
			//first load the item so we're only updating existing values
			$mongoQuery = array('Data.ID'=>$objectId);
			
			$existingDocument = $collection->findOne($mongoQuery);
			
			if($existingDocument){
				$documentId = $existingDocument['_id'];
				$saveData = $existingDocument;
				
				$saveData = $objectData;
				
				$saveData['_id'] = $documentId;
			}else{
				$saveData = $objectData;
			}
			
			try{
				if($collection->save($saveData)){
					
					$itemId = $saveData['Data']['ID'];
					if(!$itemId){
						$itemId = $saveData['_id'].'';
						$saveData['Data']['ID']=$itemId;
						$collection->save($saveData);
						
						$object->setValue('ID', $itemId);
					}
					if($itemId){
						return true;
					}else{
						return false;
					}	
				}else{
					return false;
				}	
			}catch (Exception $e){
				$object->addError( $e->getMessage(), $saveData);
				return false;
			}
			
    	}else{
    		throw new Exception("Error Saving Object - Invalid Object Type", 1);	
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
    		
    		throw new Exception("Invalid Query Object", 1);
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
		
		$object->fromArray($document, true);
		return true;
	}
}
?>
