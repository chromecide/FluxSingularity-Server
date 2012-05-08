<?php

class KernelObject{
	private $model;
	public static $KernelPath = '';
	public static $ModulePath = '';
	public static $GuestUserId = '';
	public static $KernelUserId = '';
	public static $DefaultDataSource = null;
	
	public $eventsSuspended = false;
	public $returnDefaults = true;
	private $dataSource = null;
	
	public function __construct(){
		$model = array(
			'Definitions'=>array(),
			'Attributes'=>array(),
			'Events'=>array(),
			'Data'=>null,
			'Subscribers'=>array()
		);
		
		$this->model = $model;
	}
	
	public function setModel(&$model){
		
		if(array_key_exists('Subscribers', $model)){
			
			if(count($model['Subscribers'])>0){
				foreach($model['Subscribers'] as $eventName=>$subscribers){
					foreach($subscribers as $subscriberId=>$subscriberCfg){
						/*if(is_array($subscriberCfg)){
							$subscriberObject = new KernelObject();
							$subscriberObject->setModel($subscriberCfg);
							$subscriberObject->suspendEvents();
							$subscriberObject->save();
							unset($model['Subscribers'][$eventName][$subscriberId]);
							$model['Subscribers'][$eventName][$subscriberObject->getValue('ID')] = $subscriberObject->getValue('ID');
						}*/
					}
				}		
			}
			
		}
		
		$this->model = $model;
	}
	
	public function getModel(){
		$modelRef = $this->model;
		return $modelRef;
	}
	
	
	public function toArray(){
		
	}
	
	/*
	 * Definition Methods
	 */
	public function useDefinition($definition){
		
		if(!$this->fireEvent('BeforeUseDefinition')){
			return false;
		}
		
		$ds = $this->getDataSource();
		
		if(!($definition instanceof KernelObject)){
			$definitionObject = $ds->findById($definition);
			if(!$definitionObject){
				
				$definitionObject = new KernelObject();
				$definitionObject->loadById($definition);
				$definitionObject = $definitionObject->getModel();
			}
		}else{
			$definitionObject = $definition->getModel();
		}
		
		if(!$this->usesDefinition($definitionObject)){
			$definitionModel = $definitionObject;
			$definitionID = $definitionModel['Data']['ID'];
			$definitionName = $definitionModel['Data']['Name'];
			$this->model['Definitions'][]=$definitionID;
		}
		fb($this->model);
		return $this->fireEvent('AfterUseDefinition');
	}
	
	public function usesDefinition($definition){
		
		if($definition=='Object'){
			return true;
		}
		$usesDefinition = false;
		$definitionObject = null;
		
		if(!($definition instanceof KernelObject)){
			if(is_array($definition) && array_key_exists('Data', $definition)){
				$definitionObject = new KernelObject();
				$definitionObject->setModel($definition);
			}else{
				$definitionObject = new KernelObject();
			
				$definitionObject->loadById($definition);	
			}
		}else{
			$definitionObject = $definition;
		}
		
		$definitionModel = $definitionObject->getModel();
		foreach($this->model['Definitions'] as $existingDefinitionID){
			$existingDefinition = new KernelObject();
			$existingDefinition->loadById($existingDefinitionID);
			$existingModel = $existingDefinition->getModel();
			if($existingModel['Data']['ID']==$definitionModel['Data']['ID'] || $existingDefinition->usesDefinition($definitionObject)){
				$usesDefinition = true;
				break;
			}
		}
		
		return $usesDefinition;
	}
	
	
	public function getDefinition($definitionID){
		if(in_array($definitionID, $this->model['Definitions'])){
			$definitionObject = new KernelObject();
			$definitionObject->loadById($definitionID);
			return $definitionObject;
		}else{
			fb('No Definition');
		}
	}
	
	public function removeDefinition($definition){
		foreach($this->model['Definitions'] as $existingDefinitionID){
			$existingDefinition = new KernelObject();
			$existingDefinition->loadById($existingDefinitionID);
			if($existingDefinition->getValue('ID')==$definitionObject->getValue('ID')){
				unset($this->model['Definitions'][$existingDefinitionID]);
				break;
			}
		}
	}
	/*
	 * End Definition Methods
	 */
	
	/*
	 * Attribute Methods
	 */
	public function addAttribute(){
		$args = func_get_args();
		$argCount = count($args);
		$attributeCfg = null;
		switch($argCount){
			case 1: //assume it's an attribute Cfg Array
				$attributeCfg = func_get_arg(0);
				break;
			case 2: //name, AllowedDefinitions
				$attributeCfg = array(
					'Name'=>func_get_arg(0),
					'AllowedDefinitions'=>func_get_arg(1),
					'Required'=>false,
					'IsList'=>false,
					'IsPrimitive'=>false
				);
				break;
			case 3: //name, AllowedDefinitions, Required
				$attributeCfg = array(
					'Name'=>func_get_arg(0),
					'AllowedDefinitions'=>func_get_arg(1),
					'Required'=>func_get_arg(2),
					'IsList'=>false,
					'IsPrimitive'=>false
				);
				break;
			case 4: //name, AllowedDefinitions, Required, IsList
				$attributeCfg = array(
					'Name'=>func_get_arg(0),
					'AllowedDefinitions'=>func_get_arg(1),
					'Required'=>func_get_arg(2),
					'IsList'=>func_get_arg(3),
					'IsPrimitive'=>false
				);
				break;
			case '5':
				$attributeCfg = array(
					'Name'=>func_get_arg(0),
					'AllowedDefinitions'=>func_get_arg(1),
					'Required'=>func_get_arg(2),
					'IsList'=>func_get_arg(3),
					'IsPrimitive'=>func_get_arg(4)
				);
				break;
		}
		
		if(!$this->hasAttribute($attributeCfg['Name'])){
			$this->model['Attributes'][$attributeCfg['Name']] = $attributeCfg; 
		}
		return $this;
	}
	
	public function hasAttribute($attributeName){
		$hasAttribute = false;
		if(!array_key_exists($attributeName, $this->model['Attributes'])){
			foreach($this->model['Definitions'] as $definitionID){
				$definitionObject = new KernelObject();
				$definitionObject->loadById($definitionID);
				if($definitionObject->hasAttribute($attributeName)){
					$hasAttribute = true;
				}
			}
		}else{
			$hasAttribute = true;
		}
		
		return $hasAttribute;
	}
	
	
	public function getAttribute($attributeName){
		$returnValue = null;
		if($this->model){
			if(is_array($this->model)){
				if(array_key_exists($attributeName, $this->model['Attributes'])){
					$returnValue = &$this->model['Attributes'][$attributeName];	
				}else{
					foreach($this->model['Definitions'] as $definitionID){
						$definition = new KernelObject();
						$definition->loadById($definitionID);
						
						$returnValue = &$definition->getAttribute($attributeName);
						if($returnValue){
							break;
						}
					}	
				}	
			}
		}
		
		return $returnValue;
	}
	
	public function getAttributes(){
		$attributeArray = $this->model['Attributes'];
		if(!$attributeArray){
			$attributeArray = array();
		}
		foreach($this->model['Definitions'] as $definitionID){
			$definition = new KernelObject();
			$definition->loadById($definitionID);
			$attributeArray = array_merge($definition->getAttributes(), $attributeArray);
		}
		return $attributeArray;
	}
	
	public function removeAttribute($attributeName){
		if(!array_key_exists($attributeName, $this->model['Attributes'])){
			unset($this->model['Attributes'][$attributeName]);	
		}
	}
	/*
	 * End Attribute Methods
	 */
	
	/*
	 * Event Methods
	 */ 
	 
	public function addEvent($eventName){
		$args = func_get_args();
		$argCount = count($args);
		if(!$this->hasEvent($eventName)){
			$this->model['Events'][] = $eventName;
			$this->model['Events'][] = $eventName.'Instance';
 		}
	}
	
	public function hasEvent($eventName){
		$hasEvent = false;
		if($this->model){
			if(array_key_exists('Events', $this->model)){
				if(in_array($eventName, $this->model['Events'])){
					$hasEvent = true;
				}else{
					if(array_key_exists('Definitions', $this->model)){
						foreach($this->model['Definitions'] as $definitionID){
							$definition = new KernelObject();
							$definition->loadById($definitionID);
							
							if($definition->hasEvent($eventName)){
								$hasEvent = true;
								break;
							}
						}	
					}
				}	
			}else{
				if(array_key_exists('Definitions', $this->model)){
					foreach($this->model['Definitions'] as $definitionID){
						$definition = new KernelObject();
						$definition->loadById($definitionID);
						
						if($definition->hasEvent($eventName)){
							$hasEvent = true;
							break;
						}
					}	
				}
			}	
		}
		
		return $hasEvent;
	}
	
	public function getEvent($eventName){
		if(is_array($this->model['Events'])){
			if(array_key_exists($eventName, $this->model['Events'])){
				$eventModel = $this->model['Events'][$eventName];
				$eventObject = new KernelObject();
				$eventObject->setModel($eventModel);
				//fb($eventObject);
			}
		}
	}
	
	public function getEvents(){
		if(is_array($this->model['Events'])){
			return $this->model['Events'];
		}
	}
	
	public function removeEvent(){
		
	}
	
	public function suspendEvents(){
		$this->eventsSuspended = true;
	}
	
	public function resumeEvents(){
		$this->eventsSuspended = false;
	}
	
	public function fireEvent($eventName, $numPlaces=0){
		$placeString = ' ';
		$placeString.=str_repeat('-', $numPlaces);
		if(!$this->eventsSuspended){
			if($this->hasEvent($eventName)){
				fb($placeString.'Firing Event: '.$this->getValue('Name').'::'.$eventName);
				
				$eventObject = $this->getEvent($eventName);
				$subscribers = $this->getSubscribers($eventName, $this);
				$definitionSubscribers = array();
				$definitionEventName = $eventName;
				
				foreach($this->model['Definitions'] as $definitionID){
					$definitionObject = $this->getDefinition($definitionID);
					if(substr($eventName, strlen($eventName)-8, 8)!='Instance'){
						$definitionEventName = $eventName.'Instance';
					}
					
					if($definitionObject->hasEvent($definitionEventName)){
						$definitionSubscribers = array_merge($definitionObject->getSubscribers($definitionEventName, $this), $definitionSubscribers);
					}
				}
				
				$continue = true;
				foreach($definitionSubscribers as $definitionSubscriber){
					if(substr($eventName, strlen($eventName)-8, 8)!='Instance'){
						$instanceSubscriber = $definitionSubscriber;
					}else{
						$instanceSubscriber = new KernelObject();
						$instanceSubscriber->useDefinition($definitionSubscriber);	
					}
					
					if($continue){
						$continue = $instanceSubscriber->notify($definitionEventName, &$this);
					}
				}
				
				foreach($subscribers as $subscriber){
					if($continue){
						$subscriber->notify($eventName, $this);
					}
				}
				
				return $continue;
			}else{
				return true;
			}
				
		}else{
			
			return true;
		}	
	}
	
	protected function beforeRun(&$inputObject){
		$this->ActionParent = $inputObject;
		$continue = false;
		//handle any action mapping
		if($this->usesDefinition('Object.Action')){
			$conditions = $this->getValue('Conditions');
			$objectMap = $this->getValue('ObjectMap');
			
			if($conditions){
				if(!$this->validateConditions($conditions)){
					$continue = false;
				}
			}
			if($objectMap){
				foreach($objectMap as $mapping){
					if(is_array($mapping)){
						$mappingObject = new KernelObject();
						$mappingObject->setModel($mapping);
						$mapping = $mappingObject;
					}
					
					
					$sourceAttribute = $mapping->getValue('Source');
					
					$targetAttributes = $mapping->getValue('Targets');
					$mapValue = null;
					
					if(strpos($sourceAttribute, '.')){
						$sourceParts = explode('.', $sourceAttribute, 2);	
					}else{
						$sourceParts = array($sourceAttribute);
					}
					
					if(count($sourceParts)>1){
						$sourceAttribute = $sourceParts[0];
						$sourceSubAttribute = $sourceParts[1];
					}else{
						$sourceAttribute = $sourceParts[0];
						$sourceSubAttribute = false;	
					}
					
					
					switch($sourceAttribute){
						case 'InputObject':
							if($sourceSubAttribute){
								$mapValue = $inputObject->getValue($sourceSubAttribute);
							}else{
								$mapValue = $inputObject;
							}
							break;
						default:
							if($sourceSubAttribute){
								$subItem = $this->getValue($sourceAttribute);
								$mapValue = $subItem->getValue($sourceSubAttribute);
								
							}else{
								$mapValue = $this->getValue($sourceAttribute);
							}
							break;
					}
					
					foreach($targetAttributes as $target){
						$this->setValue($target, $mapValue);
						$this->suspendEvents();
						$this->save();
						$this->resumeEvents();
					}
				}	
			}
			$continue = true;
		}
		
		if($continue){
			$return = $this->fireEvent('BeforeRun');
			
			return $return;
		}else{
			return false;
		}
	}
	
	protected function afterRun(&$inputObject){
		$ran = $this->fireEvent('AfterRun');
		
		return $ran;
	}
	
	public function notify($eventName, $eventObject){
		
		fb(' -- Recieved Notification: '.$this->getValue('ID').'::'.$eventName);
		if($eventObject->getValue('ID')==''){
			//fb($eventObject);
		}else{
			fb(' --- From: '.$eventObject->getValue('ID'));
			//fb($this->model);
			//fb($this->usesDefinition('Object.Action'));
			//fb($this->getActionObject());
		}
		
		if($this->usesDefinition('Object.Action')){
			
			$actionObject = $this->getActionObject();
			
			if($actionObject){
				
				$actionObject->setModel($this->getModel());
				if($actionObject->run($eventObject)){
					$this->setModel($actionObject->getModel());	
				}
			}
		}else{
			//TODO: need to handle notification for non-action objects
		}
	}
	
	public function getActionObject(){
		$actionObject = false;
		if($this->getValue('FileName')==''){
			if($this->usesDefinition('Object.Action')){
				$actionObject = $this->loadFromFile($this->getValue('ID'));
				
				if(!$actionObject){ //one of the definitions used must be the action object
					
					foreach($this->model['Definitions'] as $definitionID){
						$definition = new KernelObject();
						$definition->loadById($definitionID);
						if($definition->usesDefinition('Object.Action')){
							$actionObject = $definition->getActionObject();
							
							if($actionObject){
								$actionObject->setModel($this->getModel());
								break;
							}	
						}
					}
				}else{
					$actionObject->setModel($this->getModel());
				}
					
			}else{
				fb('No use Action: ');
				fb($this->model);
			}
		}else{
			//just load directly from the file
			fb('Loading directly from file');
		}
		
		
		return $actionObject;
	}
	/* 
	 * End Event Methods
	 */
	
	/*
	 * Subscription Methods
	 */
	public function addSubscriber($eventName, $subscriberObject, $objectMap=array(), $conditions=array()){
		if(!array_key_exists($eventName, $this->model['Subscribers'])){
			$this->model['Subscribers'][$eventName] = array();
		}
		
		if($subscriberObject->getValue('ID')){
			$subscriberObject->save();
		}
		
		$this->model['Subscribers'][$eventName][$subscriberObject->getValue('ID')] = array(
				'ID'=>$subscriberObject->getValue('ID'),
				'ObjectMap'=>$objectMap,
				'Conditions'=>$conditions
		);
	}
	
	public function getSubscribers($eventName, $eventObject){
		$returnArray = array();
		if(array_key_exists($eventName, $this->model['Subscribers'])){
			$subscriberArray = $this->model['Subscribers'][$eventName];
			if(is_array($subscriberArray)){
				foreach ($subscriberArray as $objectID=>$objectCfg) {
					if(substr($eventName, strlen($eventName)-8, 8)!='Instance'){
						$subscriberObject = new KernelObject();
						$subscriberObject->loadById($objectID);	
					}else{
						$subscriberObject = new KernelObject();
						$subscriberObject->useDefinition($objectID);
					}
					
					
					$conditionsValid = true;
						
					if(is_array($objectCfg)){
						$conditions = $objectCfg['Conditions'];
						$objectMap = $objectCfg['ObjectMap'];
						
						if($conditions){
							//if any conditions fail, set conditionsValid to false
							foreach($conditions as $condition){
								if(!$this->validateCondition($condition, $eventObject)){
									$conditionsValid = false;
									break;
								}
							}
						}
						
						if($conditionsValid){
							if($objectMap){
								foreach($objectMap as $mapping){
									$mapSource = $mapping['Source'];
									$mapTargets = $mapping['Targets'];
									
									$sourceValue = $eventObject->getValue($mapSource);
									
									foreach($mapTargets as $targetAttributeName){
										$subscriberObject->setValue($targetAttributeName, $sourceValue);
									}
								}
							}
						}
						
					}
					if($conditionsValid){
						$returnArray[] = $subscriberObject;	
					}
				}
			}
		}
		return $returnArray;
	}
	
	public function removeSubscriber($eventName, $actionName, $conditions){
		
	}
	
	/*
	 * End Subscription Methods
	 */
	
	
	/*
	 * Start Action Methods 
	 */
	
	public function loadFromFile($actionName, $actionCfg =false){
		if($actionName){
			$actionObject = new KernelObject();
			if($actionObject->getValue('FileName')!=''){
				//try and load the object from file
				
			}else{
				$fileString='';
				
				if(substr($actionName, 0, 6)=='Kernel'){
					$fileName = str_replace('Kernel', '', $actionName);
					$fileString = self::$KernelPath;
					$fileString.=str_replace('.', '/', $fileName).'.php';
				}else{
					if(substr($actionName, 0, 7)=='Modules'){
						$fileName = str_replace('Modules', '', $actionName);
						$fileString = self::$ModulePath;
						$fileString.=str_replace('.', '/', $fileName).'.php';
					}
				}
				$className = str_replace('.', '', $actionName);
				
				if(class_exists($className)){
					
					include_once($fileString);
					if(file_exists($fileString)){
						
						if(class_exists($className)){
							$actionObject = new $className();
							
							if($actionCfg && is_array($actionCfg) && count($actionCfg)>0){
								$actionObject->setModel($actionCfg);	
							}
	
							return $actionObject;
						}else{
							$this->addError('Could not load Action: '.$actionName, $actionCfg);
							return false;
						}
					}else{
						$this->addError('Could not Load Action: '.$actionName.'. Invalid File Name. '. $fileString);
						return false;
					}
				}else{
					if($fileString){
						include_once($fileString);
						$actionObject = new $className();
								
						if($actionCfg && is_array($actionCfg) && count($actionCfg)>0){
							$actionObject->setModel($actionCfg);	
						}	
					}else{
						$actionObject = null;
					}
					
					return $actionObject;
				}
				
			}
		}
	}

	public function validateCondition($condition, $object=null){
		$valid = false;
		if(!$object){
			$object = $this;
		}
		if($condition instanceof KernelObject){
			
		}else{
			if(is_array($condition)){
				
				$attributeName = $condition['Attribute'];
				$operator = $condition['Operator'];
				$value = $condition['Value'];
				
				$attributeValue = $object->getValue($attributeName);
				switch($conditionOperator){
					case '>':
						$valid = ($attributeValue>$value);
						break;
					case '>=':
						$valid = ($attributeValue>=$value);
						break;
					case '<':
						$valid = ($attributeValue<$value);
						break;
					case '<=':
						$valid = ($attributeValue<=$value);
						break;
					case '!=':
						$valid = ($attributeValue!=$value);
						break;
					case 'IN':
						if(is_array($attributeValue)){
							
						}
						break;
					case 'NIN':
						
						break;
					case 'EXISTS':
						
						break;
					case '==':
					default:
						fb('validating condition');
						$valid = ($attributeValue==$value);
						break;
				}
			}
		}
		
		return $valid;
	}
	/*
	 * End Action Methods 
	 */
	
	
	/*
	 * Value Methods
	 */
	
	public function addValue(){
		$args = func_get_args();
		$argCount = count($args);
		
		switch($argCount){
			case 1:
				$this->model['Data'] = func_get_arg(0);
				break;
			case 2:
				$attributeName = func_get_arg(0);
				$attributeValue = func_get_arg(1);
				
				if(!array_key_exists($attributeName, $this->model['Data']) || !is_array($this->model['Data'][$attributeName])){
					$this->model['Data'][$attributeName] = array();
				}
				
				if(is_null($attributeValue)){
					$this->model['Data'][$attributeName][] = $attributeValue;
				}else{
					
					if($this->validateValue($attributeName, $attributeValue)){
						if($attributeValue instanceof KernelObject){
							if(!$isPrimitive){
								if(!$attributeValue->getValue('ID')){
									fb('--');
									fb($attributeValue);
									die('no id');
								}else{
									$this->model['Data'][$attributeName][] = array('ID'=>$attributeValue->getValue('ID'));	
								}
							}else{
								$this->model['Data'][$attributeName][] = $attributeValue->getModel();
							}
						}else{
							$this->model['Data'][$attributeName][] = $attributeValue;
						}
					}else{
						fb('Not Valid');
						fb($attributeName);
						
						/*fb($attributeValue);
						fb($attributeCfg);
						fb($this->model);
						fb('--==--');*/
					}	
				}	
			
				break;
		}
	}
	
	public function setValue(){
		$args = func_get_args();
		$argCount = count($args);
		
		switch($argCount){
			case 0:
				$this->model['Data']=null;
				break;
			case 1:
				$this->model['Data'] = func_get_arg(0);
				break;
			case 2:
				if(!is_array($this->model['Data'])){
					$this->model['Data'] = array();
				}
				
				$attributeName = func_get_arg(0);
				$attributeValue = func_get_arg(1);
				
				$attributeCfg = $this->getAttribute($attributeName);
				
				$isList = false;
				$isPrimitive = false;
				
				if($attributeCfg){
					$isRequired = array_key_exists('IsRequired', $attributeCfg)?$attributeCfg['IsRequired']:false;
					$isPrimitive = array_key_exists('IsPrimitive', $attributeCfg)?$attributeCfg['IsPrimitive']:false;
					$isList = $attributeCfg['IsList'];
				}else{
					fb($this->model);
					fb('Attribute Not Found: '.$attributeName);
				}
				
				if($isList && is_array($attributeValue)){
					 foreach($attributeValue as $attributeValueItem){
					 	$this->addValue($attributeName, $attributeValueItem);
					 }
				}else{
					if($isList && !is_array($attributeValue) && !is_null($attributeValue) && $attributeValue!=''){
						fb('Invalid list value for: '.$attributeName);
					}else{
						if(is_null($attributeValue)){
							$this->model['Data'][$attributeName] = $attributeValue;
						}else{
							if($this->validateValue($attributeName, $attributeValue)){
								if($attributeValue instanceof KernelObject){
									if(!$isPrimitive){
										if(!$attributeValue->getValue('ID')){
											die('no id');
										}else{
											$this->model['Data'][$attributeName] = array('ID'=>$attributeValue->getValue('ID'));	
										}
									}else{
										$this->model['Data'][$attributeName] = $attributeValue->getModel();
									}
								}else{
									$this->model['Data'][$attributeName] = $attributeValue;
								}
							}else{
								fb('--==--');
								fb('Not Valid');
								fb($attributeName);
								fb($attributeValue);
								fb($attributeCfg);
								fb($this->model);
								fb('--==--');
								fb(debug_backtrace());
								fb('-==-');
							}	
						}	
					}
						
				}
				
				break;
		}
	}
	
	public function getValue(){
		$args = func_get_args();
		$argCount = count($args);
		$returnValue = null;
		
		if(func_get_arg(0)!='ID'){
			if(array_key_exists('Data', $this->model)){
				if(is_array($this->model['Data'])){
					if(array_key_exists('ID', $this->model['Data'])){
						//$this->loadById($this->model['Data']['ID']);
					}	
				}
			}	
		}
		
		switch($argCount){
			case 0:
				$returnValue = &$this->model['Data'];
				break;
			case 1: //attribute
				$attributeName = func_get_arg(0);
				$attributeCfg = $this->getAttribute($attributeName);
				$isPrimitive = false;
				
				if($attributeCfg){
					$isPrimitive = $attributeCfg['IsPrimitive'];	
				}/*else{
					return null;
				}*/
				
				$returnValue = null;
				if(strpos($attributeName, '.')>-1){
					$attributeNameString = $attributeName;
					$thisObjectAttribute = explode('.', $attributeNameString, 2);
					
					if($thisObjectAttribute[0]=='InputObject'){
						$thisObjectValue = $this->ActionParent;
					}else{
						$thisObjectValue = $this->getValue($thisObjectAttribute[0]);
					}
					$subValue = $thisObjectAttribute[1];
					$returnValue = $thisObjectValue->getValue($subValue);
				}else{
					if(is_array($this->model['Data'])){
						if(array_key_exists($attributeName, $this->model['Data'])){
							$returnValue = $this->model['Data'][$attributeName];
							
							if($isPrimitive && is_array($returnValue) && array_key_exists('Definitions', $returnValue)){
								$returnValueCfg = $returnValue;
								
								$returnValue = new KernelObject();
								$returnValue->setModel($returnValueCfg);
								
							}else{
								if(is_array($returnValue) && array_key_exists('ID', $returnValue)){
									$returnValueID = $returnValue['ID'];
									$returnValue = new KernelObject();
									$returnValue->loadById($returnValueID);
								}
							}
						}else{
							if($attributeName=='InputObject'){
								$returnValue = $this;
							}
						}
					}else{
						if($attributeName=='InputObject'){
								$returnValue = $this;
							}
						}
					
				}
				
				if(!$returnValue && $attributeName!='ID' && $this->returnDefaults){
					//see if any of the definitions have a value for this attribute
					foreach($this->model['Definitions'] as $definitionID){
						$definition = new KernelObject();
						$definition->loadById($definitionID);
						if($definition->hasAttribute(func_get_arg(0))){
							$returnValue = $definition->getValue(func_get_arg(0));
						}
					}	
				}
				break;
			case 2: //attribute, index
				$returnValue = $this->model['Data'][func_get_arg(0)];
				
				if(is_array($returnValue)){
					$returnValue = $returnValue[func_get_arg(2)];
					if(array_key_exists('Definitions', $returnValue)){
						$returnValueCfg = $returnValue;
						$returnValue = new KernelObject();
						$returnValue->setModel($returnValueCfg);
					}else{
						if(array_key_exists('ID', $returnValue)){
							$returnValueID = $returnValue['ID'];
							$returnValue = new KernelObject();
							$returnValue->loadById($returnValueID);
						}
					}
				}
				break;
			
		}

		
		
		$returnValueRef = &$returnValue;
		return $returnValueRef;
	}
	
	public function validateValue($attributeName, $attributeValue){
		
		$attributeCfg = $this->getAttribute($attributeName);
		
		$allowedDefinitions = $attributeCfg['AllowedDefinitions'];
		$isRequired = $attributeCfg['Required'];
		$isList = $attributeCfg['IsList'];
		$isPrimitive = $attributeCfg['IsPrimitive'];
		$valueValid = false;
		
		if($attributeValue instanceof KernelObject){
			foreach($allowedDefinitions as $definitionID){
				if($attributeValue->usesDefinition($definitionID)){
					$valueValid = true;
				}
			}
		}else{
			if(is_scalar($attributeValue)){
				foreach ($allowedDefinitions as $definitionID) {
					if(	$definitionID=='Object' ||
						$definitionID=='Object.String' || 
						$definitionID=='Object.Number' || 
						$definitionID=='Object.Date' || 
						$definitionID=='Object.Boolean'){
							$valueValid = true;
					}
				}
				
				if(!$valueValid){
					if($attributeValue=='' || $attributeValue==null){
						if(!$isRequired){
							$valueValid = true;
						}
					}
				}
			}else{
				fb('No handling for validating this:');
				fb($attributeValue);
			}
		}
		
		return $valueValid;
		
	}
	/*
	 * End Value Methods
	 */
	
	/*
	 * Data Source Functions
	 */
	public function setDataSource($dataSource){
		$this->dataSource = $dataSource;
	}
	
	public function getDataSource(){
		$ds = null;
		if(is_null($this->dataSource)){
			$ds = self::$DefaultDataSource;
		}else{
			$ds = $this->dataSource;
		}
		return $ds;
	}
	
	public static function setDefaultDataSource($dataSource){
		self::$DefaultDataSource = $dataSource;
	}
	
	public function loadById($objectID){
		//try to load from the db
		$ds = $this->getDataSource();
		$found = false;
		
		if(!$objectID){
			return false;
		}
		
		$object = &$ds->findById($objectID);
		
		if($object){
			$found = true;
			$this->setModel($object);
		}
		
		if(!$found){
			switch($objectID){
				case 'Object':
					$this->suspendEvents();
					$this->addAttribute('ID', array('Object.String'), true);
					$this->addAttribute('Name', array('Object.String'), true);
					$this->addAttribute('Description', array('Object.String'), true);
					$this->addAttribute('Author', array('Object.String'), true);
					$this->addAttribute('Version', array('Object.String'), true);
					
					$this->setValue('ID', 'Object');
					$this->setValue('Name', 'Object');
					$this->setValue('Description', 'Base Object definition that all other objects are built from');
					$this->setValue('Author', 'Justin Pradier');
					$this->setValue('Version', '1.0.0');
					
					$this->addEvent('BeforeSave');
					$this->addEvent('AfterSave');
					$this->addEvent('BeforeUseDefinition');
					$this->addEvent('AfterUseDefinition');
					$this->addEvent('BeforeRemove');
					$this->addEvent('AfterRemove');
					
					$this->save();
					$this->resumeEvents();
					break;
				case 'Object.Definition';
					$this->useDefinition('Object');
					$this->suspendEvents();
					$this->setValue('ID', 'Object.Definition');
					$this->setValue('Name', 'Object.Definition');
					$this->setValue('Description', 'Object Definition');
					$this->setValue('Author', 'Justin Pradier');
					$this->setValue('Version', '1.0.0');
					
					$this->save();
					break;
				case 'Object.DataSource':
					$this->suspendEvents();
					$this->addAttribute('DriverName', 'Object.String', false);
					$this->addAttribute('DatabaseName', 'Object.String', false);
					$this->addAttribute('DatabaseUser', 'Object.String', false);
					$this->addAttribute('DatabasePassword', 'Object.String', false);
					
					$this->addEvent('Connected');
					$this->addEvent('Disconnected');
					$this->addEvent('BeforeFind');
					$this->addEvent('AfterFind');
					$this->save();
					$this->resumeEvents();
					break;
				case 'Object.AttributeMap':
					$this->suspendEvents();
					$this->useDefinition('Object');
					
					$this->addAttribute('Source', array('Object.String'), true, false);
					$this->addAttribute('Targets', array('Object.String'), true, true);
					
					$this->setValue('ID', 'Object.AttributeMap');
					$this->setValue('Name', 'Object.AttributeMap');
					$this->setValue('Description', 'Object to Object Attribute Mapping');
					$this->setValue('Author', 'Justin Pradier');
					$this->setValue('Version', '1.0.0');
					
					break;
				case 'Object.Module':
					$this->suspendEvents();
					$this->useDefinition('Object');
					
					$this->setValue('ID', 'Object.Module');
					$this->setValue('Name', 'Object.Module');
					$this->setValue('Description', 'Object Module Definition');
					$this->setValue('Author', 'Justin Pradier');
					$this->setValue('Version', '1.0.0');
					
					$this->addAttribute('Actions', array('Object.String'), false, true);
					$this->addAttribute('Definitions', array('Object.String'), false, true);
					
					$this->save();
					break;
				case 'Object.Action':
					$this->suspendEvents();
					$this->useDefinition('Object');
					$this->setValue('ID', 'Object.Action');
					$this->setValue('Name', 'Object.Action');
					$this->setValue('Description', 'Base Action definition that all other Actions are built from');
					$this->setValue('Author', 'Justin Pradier');
					$this->setValue('Version', '1.0.0');
					
					$this->addAttribute('ObjectMap', array('Object.AttributeMap'), false, true);
					
					$this->addEvent('BeforeRun');
					$this->addEvent('AfterRun');
					
					$this->save();
					break;
				case 'Object.Event':
					$this->useDefinition('Object.Definition');
					$this->setValue('ID', 'Object.Event');
					$this->setValue('Name', 'Object.Event');
					$this->setValue('Description', 'Object Event');
					$this->setValue('Author', 'Justin Pradier');
					$this->setValue('Version', '1.0.0');
					$this->addAttribute('Object', array('Object'), true);
					$this->addAttribute('FiredAt', array('Object.Date'), true);
					$this->suspendEvents();
					$this->save();
					break;
				case 'Object.Condition':
					$this->useDefinition('Object.Definition');
					$this->setValue('ID', 'Object.Condition');
					$this->setValue('Name', 'Object.Condition');
					$this->setValue('Description', 'Object Condition Definition');
					$this->setValue('Author', 'Justin Pradier');
					$this->setValue('Version', '1.0.0');
					
					$this->addAttribute('AttributeName', array('Object.String'), true);
					$this->addAttribute('Operator', array('Object.String'), true);
					$this->addAttribute('Value', array('Object'));
					$this->suspendEvents();
					$this->save();
					break;
				case 'Object.ConditionGroup':
					$this->useDefinition('Object.Definition');
					$this->setValue('ID', 'Object.ConditionGroup');
					$this->setValue('Name', 'Object.ConditionGroup');
					$this->setValue('Description', 'Object Condition Group Definition');
					$this->setValue('Author', 'Justin Pradier');
					$this->setValue('Version', '1.0.0');
					
					$this->addAttribute('Type', array('Object.String'), true);
					$this->addAttribute('Conditions', array('Object.Condition', 'Object.ConditionGroup'), true, true, true);
					$this->suspendEvents();
					$this->save();
					break;
				case 'Object.Query':
					$this->useDefinition('Object.Definition');
					$this->setValue('ID', 'Object.Query');
					$this->setValue('Name', 'Object.Query');
					$this->setValue('Description', 'Object Query Definition');
					$this->setValue('Author', 'Justin Pradier');
					$this->setValue('Version', '1.0.0');
					
					$this->addAttribute('Conditions', array('Object.ConditionGroup'), true, true, true);
					$this->addAttribute('QueryString', array('Object.String'));
					$this->addAttribute('Results', array('Object'), false, true);
					$this->suspendEvents();
					$this->save();
					break;
				default:
					// attempt to load the object from file
					
					$object = $this->loadFromFile($objectID);
					
					if($object){
						$this->setModel($object->getModel());
					}
					$this->suspendEvents();
					$this->save();
					$this->resumeEvents();
					break;
			}
		}
	}
	
	public function findOne(){
		$args = func_get_args();
		$argCount = count($args);
		if($argCount==0){ //building a query from the current objects values
			$ds = $this->getDataSource();
			$result = $ds->findOne($this);
			
			if($result){
			
				$this->setModel($result);
				return true;	
			}
		}else{ //using supplied information
			
		}
		return false;
	}
	
	public function find(){
		$args = func_get_args();
		$argCount = count($args);
		if($argCount==0){ //building a query from the current objects values
			$ds = $this->getDataSource();
			$result = $ds->find($this);
		}else{ //using supplied information
			
		}
		return $result;
	}
	
	public function save(){
		if(!$this->fireEvent('BeforeSave')){
			return false;
		}
		
		$ds = $this->getDataSource();
		$saved = $ds->save(&$this->model);
		
		if($saved){
			return $this->fireEvent('AfterSave');
		}
		return $saved;
	}
	
	public function remove(){
		$ds = $this->getDataSource();
		$saved = $ds->remove($this->model);
	}
	
	public function addError(){
		
	}
}
?>