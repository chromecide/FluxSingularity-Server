<?php

class KernelDataDriverLocalProcess extends KernelDataDriver {
	public static $objectCache = array();
	
	function __construct($config){
		parent::__construct($config);
		$this->config = $config;
	}
	
	public function connect(){
		return true;
	}
	
	public function disconnect(){
		return true;
	}
	
    public function &findById($objectId){
    	
    	if($objectId){
    		if(array_key_exists($objectId, self::$objectCache)){
    			$returnObject = &self::$objectCache[$objectId];
				return $returnObject;
			}
    	}else{
    		fb(debug_backtrace());
    		throw new Exception("Invalid Query Object (findById)", 1);
    	}
		
		$returnValue = false;
		return $returnValue;
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
    	
		return $returnArray;
    }
    
    public function findOne(&$queryObject){
    	$returnModel = null;
		
		$attributeList = $queryObject->getAttributes();
		$conditions = array();
		
		foreach($attributeList as $attributeID=>$attributeCfg){
			$queryObject->returnDefaults = false;
			$attrValue = $queryObject->getValue($attributeID); 
			if($attrValue){
				$conditions[$attributeID] = $attrValue;
			}
		}
		
		foreach(self::$objectCache as $result){
			$resultMatch = true;
			foreach($conditions as $attrName=>$attrValue){
				if($result['Data'][$attrName]!=$attrValue){
					$resultMatch = false;
				}
			}
			if($resultMatch){
				$returnModel = $result;
				break;
			}
		}
		
		if($returnModel){
			return $returnModel;
    	}else{
    		throw new Exception("Invalid Query Object (findOne)", 1);
    	}
    }

    public function save(&$model){
    	$collectionName = $this->getObjectCollectionName();
		
		$objectId = false;
		
		if(array_key_exists('ID', $model['Data'])){
			$objectId = $model['Data']['ID'];	
		}
		
		if(!$objectId){
			$model['Data']['ID'] = uniqid();
		}
		
		$mongoQuery = array('Data.ID'=>$objectId);
		
		//$existingDocument = $collection->findOne($mongoQuery);
		if(!array_key_exists($model['Data']['ID'], self::$objectCache)){
			self::$objectCache[$model['Data']['ID']] = &$model;
		}else{
			self::$objectCache[$model['Data']['ID']] = &$model;
		}
		return true;
		/*if($collection->save($model)){
			$itemId = $model['Data']['ID'];
			if(!$itemId){
				$itemId = $model['_id'].'';
				$model['Data']['ID']=$itemId;
			}
			if(!array_key_exists($model['Data']['ID'], self::$objectCache)){
				self::$objectCache[$model['Data']['ID']]=$model;
			}
		}*/
    }
	
    public function save2(&$object){
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
		
		$object->fromArray($document, true);
		return true;
	}
	
	private function objectToQuery($queryObject){
		$mongoQuery = false;
		if($queryObject instanceof KernelObject){
    		if($queryObject->usesDefinition('Object.Query')){
    			$mongoQuery = array();
				$conditions = $queryObject->getValue('Conditions');
				
				$conditionList = array();
				
				foreach($conditions as $condition){
					if($condition->usesDefinition('Object.Query')){
						$conditionConfig = $this->objectToQuery($condition);
					}else{
						$conditionConfig = array();
						$conditionFieldName = $condition->getValue('Attribute');
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
