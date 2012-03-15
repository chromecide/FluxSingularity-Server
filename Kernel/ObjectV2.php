<?php

class KernelObject{
	public static $KernelUserId;
	public static $GuestUserId;
	public static $DefaultDataDriver;
	protected $DataDriver;
	
	protected $ObjectId;
	protected $Name;
	protected $Description;
	protected $Author;
	protected $Version;
	
	protected $Errors;
	
	protected $Definitions;
	protected $Extends;
	
	protected $Events;
	protected $Actions;
	protected $Attributes;
	
	protected $AttributeValues;
	protected $Permissions;
	
	protected $eventsSuspended;
	
	public static $KernelPath = '';
	public static $ModulePath = '';
	
	protected $ReservedDefinitions = array(
		'Object',
		'Object.Definition',
		'Object.Event',
		'Object.Action',
		'Object.Attribute',
		'Object.Permission',
		'Object.Condition',
		'Object.Query',
		'Object.Security.User',
		'Object.Security.Network',
		'Object.String',
		'Object.Number',
		'Object.Date',
		'Object.Boolean',
		'Object.Module',
		'API.Session',
		'API.Read',
		'API.Update',
		'API.Remove'
	);
	
	protected $ReservedActions = array(
		'Kernel.Actions.UpdateAPISession',
		'Kernel.Actions.Stop'
	);
	
	public function __construct($cfg=null){
		if(!self::$KernelPath){
			self::$KernelPath = dirname(__FILE__);
		}
		
		if(!self::$ModulePath){
			self::$ModulePath = dirname(__FILE__).'/../Modules';
		}
		
		if(!is_null($cfg)){
			if(is_scalar($cfg)){
				
				$this->useDefinition($cfg);
				
			}else{
				if(is_object($cfg)){
					if($cfg instanceof KernelObject){
						if($cfg->usesDefinition('Object.Definition')){
							$this->useDefinition($cfg);
						}
					}else{
						$this->addError('Invalid Configuration Object', $cfg);
					}
				}else{
					if(is_array($cfg)){//assume it's a configuration array
						$this->fromArray($cfg);
					}
				}	
			}
		}
		$this->suspendEvents();
		$this->useReservedDefinition('Object');
		$this->resumeEvents();
	}
	
	public function load($id){
		if(!$this->fireEvent('BeforeLoad')){return false;}
		
		$isReserved = false;
		$dataSource = $this->getDataSource();
		
		if(in_array($id, $this->ReservedDefinitions)){
			//load the built in objects first
			$this->useReservedDefinition($id);
			$isReserved = true;
		}
		
		if($id!=''){
			$this->setValue('ID', $id);
		
			if(!$dataSource->loadById($this)){
				if(!$isReserved){
					return false;
				}
			}
		}
		
		return $this->fireEvent('AfterLoad');
	}
	
	public function save(){
		
		if(!$this->fireEvent('BeforeSave')){return false;}
		
		$isReserved = false;
		$dataSource = $this->getDataSource();
		
		if(!$dataSource->save($this)){
			return false;
		}
		
		return $this->fireEvent('AfterSave');
	}
	
	public function findOne(){
		if(!$this->fireEvent('BeforeFindOne')){return false;}
		$dataSource = $this->getDataSource();
		if(!$dataSource->findOne($this)){
			return false;
		}
		return $this->fireEvent('AfterFindOne');
	}
	
	public function find(){
		if(!$this->fireEvent('BeforeFind')){return false;}
		$dataSource = $this->getDataSource();
		
		if($dataSource){
			if(!$dataSource->find($this)){
				return false;
			}
			
			return $this->fireEvent('AfterFind');	
		}else{
			$this->addError('No Data Source Found');
			return false;
		}
	}
	
	public function remove(){
		if(!$this->fireEvent('BeforeRemove')){return false;}
		
		$dataSource = $this->getDataSource();
		
		if($dataSource){
			if(!$dataSource->remove($this)){
				return false;
			}
		}
		
		return $this->fireEvent('AfterRemove');
	}
	
	private function useReservedDefinition($name){
		$this->Definitions[$name] = array();
		switch($name){
			case 'Object.String':
			case 'Object.Number':
			case 'Object.Date':
			case 'Object.Boolean':
			case 'Object.Definition':
				break;
			case 'Object':
				$this->addAttribute('ID', 'Object.String', false, false, true);
				$this->addAttribute('Name', 'Object.String', true, false, true);
				$this->addAttribute('Description', 'Object.String', true, false, true);
				$this->addAttribute('Version', 'Object.String', true, false, true);
				$this->addEvent('BeforeSave', array('Source'=>'Object'));
				$this->addEvent('AfterSave', array('Source'=>'Object'));
				$this->addEvent('BeforeLoad', array('Source'=>'Object'));
				$this->addEvent('AfterLoad', array('Source'=>'Object'));
				$this->addEvent('BeforeRemove', array('Source'=>'Object'));
				$this->addEvent('AfterRemove', array('Source'=>'Object'));
				break;
			case 'Object.Action':
				$this->addAttribute('Event', 'Object.Event', true, false);
				$this->addAttribute('Conditions', 'Object.Condition', false, true);
				$this->addAttribute('ObjectMap', 'Object', false, true);
				$this->addEvent('BeforeRun', array('Source'=>'Object.Action'));
				$this->addEvent('AfterRun', array('Source'=>'Object.Action'));
				break;
			case 'Object.Permission':
				$this->addAttribute('Recipient', 'Object');
				$this->addAttribute('Read', 'Object.Boolean');
				$this->addAttribute('Update', 'Object.Boolean');
				$this->addAttribute('Delete', 'Object.Boolean');
				$this->addAttribute('Create', 'Object.Boolean');
				break;
			case 'Object.Condition':
				$this->addAttribute('Attribute', 'Object.String', true, false, true);
				$this->addAttribute('Operator', 'Object.String', true, false, true);
				$this->addAttribute('Value', 'Object', true, true, true);
				break;
			case 'Object.Query':
				$this->addAttribute('Conditions', 'Object.Condition', true, true);
				$this->addAttribute('Results', 'Object', true, true);
				break;
			case 'Object.Security.User':
				$this->addAttribute('Username', 'Object.String', true, false, 'User Name');
				$this->addAttribute('Password', 'Object.String', true, false, 'Password');
				
				$this->addEvent('SessionStarted', array('Source'=>'Object.Security.User'));
				$this->addEvent('SessionEnded', array('Source'=>'Object.Security.User'));
				break;
			case 'Object.Security.Network':
				$this->addAttribute('Users', 'Object.Security.User', false, true);
				$this->addAttribute('Networks', 'Object.Security.Network', false, true);
				break;
			case 'Object.Module':
				$this->addAttribute('DefinitionList', 'Object.Definition', true, true, false, 'Definition List');
				$this->addAttribute('ActionList', 'Object.Action', true, true, false, 'Action List');
				break;
			case 'API.Session':
				$this->addAttribute('User', 'Object.Security.User', false, false, false);
				$this->addAttribute('LastAccessed', 'Object.Date', true);
				$this->addAttribute('LinkedSessions', 'API.Session', false, true, false);
				$this->addEvent('NewSession');
				$this->addAction('NewSession', 'Generate Start Tokens', 'Modules.FSManager.Actions.GenerateSessionTokens');
				break;
			case 'API.Read':
				$this->addAttribute('Session', 'Modules.FSManager.Object.Session', true);
				$this->addAttribute('Token', 'Object.String', true);
				//$this->addAction('BeforeSave', 'Update API Session', 'Kernel.Actions.UpdateAPISession');
				//$this->addAction('BeforeSave', 'Prevent Save', 'Kernel.Actions.Stop');
				break;
			case 'API.Update':
				$this->addAttribute('Session', 'Modules.FSManager.Object.Session', true);
				$this->addAttribute('Token', 'Object.String', true);
				//$this->addAction('BeforeSave', 'Update API Session', 'Kernel.Actions.UpdateAPISession');
				//$this->addAction('BeforeSave', 'Prevent Save', 'Kernel.Actions.Stop');
				break;
			case 'API.Remove':
				$this->addAttribute('Session', 'Modules.FSManager.Object.Session', true);
				$this->addAttribute('Token', 'Object.String', true);
				//$this->addAction('BeforeSave', 'Update API Session', 'Kernel.Actions.UpdateAPISession');
				//$this->addAction('BeforeSave', 'Prevent Save', 'Kernel.Actions.Stop');
				break;
		}
	}
	
	public static function setDefaultDataSource($sourceObject){
		if($sourceObject instanceof KernelDataDriver){
			self::$DefaultDataDriver = $sourceObject;	
		}else{
			$this->addError('Could not set Default Data Source. Invalid Source Object', $sourceObject);
		}
	}
	
	public function setDataSource($sourceObject){
		if($sourceObject instanceof KernelDataDriver){
			$this->DataDriver = $sourceObject;	
		}else{
			$this->addError('Could not set Default Data Source. Invalid Source Object', $sourceObject);
		}
	}
	
	public function getDataSource(){
		if(!$this->DataDriver){
			return self::$DefaultDataDriver;
		}else{
			return $this->DataDriver;
		}
	}
	
	public function hasAttribute($attributeName){
		if(is_array($this->Attributes)){
			return array_key_exists($attributeName, $this->Attributes);
		}
		
		return false;
	}
	
	public function addAttribute($attributeName, $attributeDefinition=array('Object'), $attributeRequired=false, $attributeIsList=false, $attributeIsPrimitive=true, $attributeLabel = false){
		
		if(!$this->fireEvent('BeforeAddAttribute', $this)){return false;}
		if(!is_array($attributeDefinition)){
			$attributeDefinition = (array) $attributeDefinition;	
		}
		
		if(!is_array($this->Attributes)){
			$this->Attributes = array();
		}
		
		if($attributeName instanceof KernelObjectattributeDefinition){
			if(!array_key_exists($attributeName->getName(), $this->Attributes)){
				$this->Attributes[$attributeName->getName()] = $attributeName;
			}else{
				return false;
			}
		}else{
			if(!array_key_exists($attributeName, $this->Attributes)){
				$newAttribute = array(
					'Name'=>$attributeName,
					'Definitions'=>$attributeDefinition,
					'Required'=>$attributeRequired,
					'IsList'=>$attributeIsList,
					'Label'=>$attributeLabel,
					'IsPrimitive'=>$attributeIsPrimitive
				);
				
				$this->Attributes[$attributeName] = $newAttribute;
			}else{
				return false;
			}
		}
		return $this->fireEvent('AfterAddAttribute', $this);
	}
	
	public function updateAttribute($attributeName, $attributeDefinition=array('Object'), $attributeRequired=false, $attributeIsList=false, $attributeIsPrimitive=true, $attributeLabel = false){
		
		if(!$this->fireEvent('BeforeUpdateAttribute', $this)){return false;}
		if(!is_array($attributeDefinition)){
			$attributeDefinition = (array) $attributeDefinition;	
		}
		
		if($attributeName instanceof KernelObjectAttributeDefinition){
			if(!array_key_exists($attributeName->getName(), $this->Attributes)){
				$this->Attributes[$attributeName->getName()] = $attributeName;
			}else{
				return false;
			}
		}else{
			if(array_key_exists($attributeName, $this->Attributes)){
				$newAttribute = array(
					'Name'=>$attributeName,
					'Definitions'=>$attributeDefinition,
					'Required'=>$attributeRequired,
					'IsList'=>$attributeIsList,
					'Label'=>$attributeLabel,
					'IsPrimitive'=>$attributeIsPrimitive
				);
				
				$this->Attributes[$attributeName] = $newAttribute;
			}else{
				return false;
			}
		}
		return $this->fireEvent('AfterUpdateAttribute', $this);
	}
	
	public function getDefinitions(){
		
		return $this->Definitions;
	}
	
	public function getAttributes(){
		return $this->Attributes;
	}
	
	
	public function getAttribute($attributeName){
		if(is_array($this->Attributes)){
			if(array_key_exists($attributeName, $this->Attributes)){
				return $this->Attributes[$attributeName];
			}
		}
			
		
		return null;
	}
	
	public function removeAttribute($attributeName){
		if(!$this->fireEvent('BeforeRemoveAttribute')){return false;}
		
		if(is_array($this->Attributes)){
			if(array_key_exists($attributeName, $this->Attributes)){
				unset($this->Attributes[$attributeName]);
			}
		}
		
		return $this->fireEvent('AfterRemoveAttribute');
	}
	
	
	public function useDefinition($object){
		
		if(!$this->fireEvent('BeforeUseDefinition')){return false;}
		
		
		if(!is_array($this->Definitions)){
			$this->Definitions = array();
		}
		
		if(is_scalar($object)){
			if(!in_array($object, $this->ReservedDefinitions)){
				$definitionObject = new KernelObject();
				if($definitionObject->load($object)){
					$this->Definitions[$object] = $definitionObject;
				}else{
					$this->addError('Could not Use Definition: '.$object, $definitionObject);
					return false;
				}	
			}else{
				return $this->useReservedDefinition($object);
			}
		}else{
			if($object instanceof KernelObject){
				if($object->usesDefinition('Object.Definition')){
					if(!$this->usesDefinition($object->getValue('ID'))){
						$definitionObject = $object;
					}
				}else{
					$this->addError('Not a Valid Definition Object', $object);
					return false;
				}
			}else{
				$this->addError('Not a Valid Definition Object', $object);
				return false;
			}	
		}
		
		if($definitionObject){
			//definitions & attributes
			$attributeList = $definitionObject->getAttributes();	
			if(is_array($attributeList)){
				foreach($attributeList as $attributeItem){
					$attrName = array_key_exists('Name', $attributeItem)?$attributeItem['Name']: null;
					$attrDefinitions = array_key_exists('Definitions', $attributeItem)?$attributeItem['Definitions']: null;
					$attrRequired = array_key_exists('IsRequired', $attributeItem)?$attributeItem['IsRequired']: false;
					$attrIsList = array_key_exists('IsList', $attributeItem)?$attributeItem['IsList']: false;
					$attrIsPrimitive = array_key_exists('IsPrimitive', $attributeItem)?$attributeItem['IsPrimitive']: false;
					$attrLabel = array_key_exists('Label', $attributeItem)?$attributeItem['Label']: null;
					$this->addAttribute($attrName, $attrDefinitions, $attrRequired, $attrIsList, $attrIsPrimitive, $attrLabel);
				}
			}
			
			//events
			$eventList = $definitionObject->getEvents();
			
			if(is_array($eventList)){
				foreach($eventList as $eventName=>$eventCfg){
					$this->addEvent($eventName, $eventCfg);
				}
			}
			
			//actions
			$actionList = $definitionObject->getActions();
			
			if(is_array($actionList)){
				foreach($actionList as $eventName=>$action){
					foreach($action as $actionName=>$actionCfg){
						//echo 'adding action';
						$actionObject = new KernelObject('Object.Action');
						
						//$conditions = array_key_exists('Conditions', $actionCfg)?$actionCfg['Conditions']:array();
						//$mapping = array_key_exists('ObjectMap', $actionCfg)?$actionCfg['ObjectMap']:array();
						$this->addAction($eventName, $actionObject);	
					}
				}
			}
		}
		
		return $this->fireEvent('AfterUseDefinition');
		
		return true;
	}
	
	public function usesDefinition($definitionID){
		if(is_array($this->Definitions)){
			return array_key_exists($definitionID, $this->Definitions);	
		}
		return false;
	}
	
	/*
	public function extend($object){
		if(!$this->fireEvent('BeforeExtend')){return false;}
		
		if(is_scalar($object)){
			if(in_array($object, $this->ReservedDefinitions)){
				
			}else{
				$query = new KernelObject('Object.Query');
			}
		}else{
			if($object instanceof KernelObject){
				$this->Extends[$object->getValue('ID')] = $object;
			}else{
				$this->addError('Cannot Extend Invalid Object', $object);
			}	
		}
		
		if(!$this->fireEvent('AfterExtend')){return false;}
	}*/
	
	public function doesExtend($object){
		$id=null;
		if(is_array($this->Extends)){
			if(is_scalar($object)){
				$id = $object;
			}else{
				if($object instanceof KernelObject){
					$id = $object->getValue('ID');		
				}
			}
			
			if(!is_null($id)){
				return array_key_exists($id, $this->Extends);
			}
		}
		
		return false;
	}
	
	public function getActions($eventName=null){
		
		$actions = null;
		if(!is_null($eventName)){
			if(is_array($this->Actions)){
				if(array_key_exists($eventName, $this->Actions)){
					$actions = $this->Actions[$eventName];
				}
			}
		}else{
			$actions = $this->Actions;	
		}
		
		return $actions;
	}
	
	public function addAction($eventName, $actionLabel, $actionName, $actionCfg){
		if(!$this->fireEvent('BeforeAddAction')){return false;}
		
		if(!is_array($this->Actions)){
			$this->Actions = array();
		}
		
		if(!array_key_exists($eventName, $this->Events)){
			$this->addEvent($eventName, array('Source'=>'User'));
		}
		
		if(!array_key_exists($eventName, $this->Actions)){
			$this->Actions[$eventName] = array();
		}
		
		if(array_key_exists($eventName, $this->Actions)){
			$events = $this->Actions[$eventName];
			if(is_scalar($actionName)){
				if(!array_key_exists($actionLabel, $events)){
					$events[$actionLabel] = array();
					
					//create an action object
					$actionObject = $this->loadAction($actionName, $actionCfg);
					
					$events[$actionLabel] = $actionObject;
					
					$this->Actions[$eventName] = $events;
				}	
			}else{
				if($actionName instanceof KernelObject){
					$events[$actionLabel] = $actionName;
					$this->Actions[$eventName] = $events;
				}else{
					if(is_array($actionLabel)){
						//need to parse an option config
					}
				}
			}
			
		}
		
		return $this->fireEvent('AfterAddAction');
	}
	
	
	public function removeAction($eventName, $actionName){
		if(is_array($this->Actions)){
			if(array_key_exists($eventName, $this->Actions)){
				$events = &$this->Actions[$eventName];
				if(array_key_exists($actionName, $events)){
					unset($events[$actionName]);
				}
			}
		}
	}
	
	public function hasEvent($eventName){
		if(is_array($this->Events)){
			return array_key_exists($eventName, $this->Events);
		}
		
		return false;
	}
	
	public function addEvent($eventName, $eventCfg=false){
		if(!$this->hasEvent($eventName)){
			$this->Events[$eventName] = array('Name'=>$eventName, 'Source'=>($eventCfg?$eventCfg['Source']: 'User'));
		}
		
		return true;
	}
	
	public function getEvent($eventName, $eventDefinition){
		if(is_array($this->Events)){
			if(array_key_exists($eventName, $this->Events)){
				return $this->Events[$eventName];
			}	
		}
		
		return null;
	}
	
	public function getEvents(){
		return $this->Events;	
	}
	
	public function addPermission($recipient, $read=true, $update=false, $remove=false, $create=false){
		if(!$this->fireEvent('BeforeAddPermission')){return false;}
		
		if($recipient instanceof KernelObject){
			if($recipient->usesDefinition('Object.Permission')){
				$permissionObject = $recipient;
			}else{
				$permissionObject = new KernelObject('Object.Permission');
				$permissionObject->setValue('Name', 'Object Permission');
				$permissionObject->setValue('Recipient', $recipient);
				$permissionObject->setValue('Create', $create);
				$permissionObject->setValue('Read', $read);
				$permissionObject->setValue('Update', $update);
				$permissionObject->setValue('Delete', $remove);
			}
		}else{
			if(is_scalar($recipient)){
				$recipientId = $recipient;
				$recipient = new KernelObject();
				if(!$recipient->load($recipientId)){
					$this->addError('Invalid Recipient', $recipientId);
					return false;
				}
			}else{
				$this->addError('Invalid Recipient', $recipient);
				return false;
			}
			
			$permissionObject = new KernelObject('Object.Permission');
			$permissionObject->setValue('Name', 'Object Permission');
			$permissionObject->setValue('Recipient', $recipient);
			$permissionObject->setValue('Create', $create);
			$permissionObject->setValue('Read', $read);
			$permissionObject->setValue('Update', $update);
			$permissionObject->setValue('Delete', $remove);
		}
		
		
		
		
		
		$this->Permissions[] = $permissionObject;
		
		return $this->fireEvent('AfterAddPermission');
	}
	
	public function getPermissions(){
		return $this->Permissions;
	}
	
	public function addError($msg, $input=null){
		echo $msg.'<br/>';
		//print_r($input);
		$this->Errors[] = array($msg, $input);
	}
	
	public function addTrace($source, $message, $level=1){
		
	}
	
	
	//ADDING SUPPORT FOR NESTED DATA RETRIEVEL (i.e. getValue('User.Name'))
	public function getValue($attributeName=null){
		$returnValue = null;
		if(is_array($this->AttributeValues)){
			if(array_key_exists($attributeName, $this->AttributeValues)){
				$returnValue = $this->AttributeValues[$attributeName];
			}
		}
		return $returnValue;
	}
	
	
	private function runValuePreProcessor($attributeName, $attributeValue, $updateExistingDefinitions=false){
		if(!is_array($this->Attributes)){
			$this->Attributes = array();
		}
		if(!array_key_exists($attributeName, $this->Attributes) || $updateExistingDefinitions){
			$attributeDefinitions = array(
				'Object'
			);
			
			if($updateExistingDefinitions && array_key_exists($attributeName, $this->Attributes)){
				$attributeCfg = $this->getAttribute($attributeName);
				$attributeDefinitions = $attributeCfg['Definitions'];
			}
			
			if(is_scalar($attributeValue)){
				if(is_numeric($attributeValue)){
					$attributeDefinitions[] = 'Object.Number';
				}else{
					if(is_bool($attributeValue)){
						$attributeDefinitions[] = 'Object.Boolean';
					}else{
						$attributeDefinitions[] = 'Object.String';
					}	
				}
			}else{
				if($attributeValue instanceof KernelObject){
					$attributeDefinitions = $attributeValue->getDefinitions();
				}else{
					if(is_array($attributeValue)){
						$attributeCfg = $this->getAttribute($attributeName);
						
						if($attributeCfg && is_array($attributeCfg)){
							if($attributeCfg['IsList']){
								foreach($attributeValue as $itemValue){
									if($continue){
										$continue = $this->runValuePreProcessor($attributeName, $itemValue, true);	
									}
								}
								if(!$continue){
									return false;
								}
							}else{
								//we can assume it is an object configuration array, so attempt to create a KernelObject from it
								$newValue = new KernelObject();
								if($newValue->fromArray($attributeValue)){
									$attributeValue = $newValue;
									$attributeDefinitions = $newValue->getDefinitions();
								}
							}
						}
					}else{
						$this->addError('Invalid Attribute Value Supplied for: '.$attributeName, $attributeValue);
						return false;	
					}
				}
			}

			if($updateExistingDefinitions && array_key_exists($attributeName, $this->Attributes)){
				$required = $attributeCfg['Required'];
				$isList = $attributeCfg['IsList'];
				$isPrimitive = $attributeCfg['IsPrimitive'];
				$label = $attributeCfg['Label'];
				
				$this->updateAttribute($attributeName, $attributeDefinitions, $required, $isList, $isPrimitive, $label);
			}else{
				$this->addAttribute($attributeName, $attributeDefinitions);	
			}
		}
		
		if(!is_array($this->AttributeValues)){
			$this->AttributeValues = array();
		}
		
		return true;
	}
	
	public function addValue($attributeName, $attributeValue){
		
		if(!$this->runValuePreProcessor($attributeName, $attributeValue)){
			
			return false;
		}
		
		$attributeCfg = $this->getAttribute($attributeName);
		
		if(!$attributeValue instanceof KernelObject){
			switch($attributeName){
				case 'ID':
				case 'Name':
				case 'Description':
				case 'Author':
				case 'Version':
					
					break;
				default:
					if(in_array('Object.String', $attributeCfg['Definitions']) || in_array('Object.Number', $attributeCfg['Definitions']) ||
					 		in_array('Object.Date', $attributeCfg['Definitions']) || in_array('Object.Boolean', $attributeCfg['Definitions'])){
						
					}else{
						$attributeID = $attributeValue['ID'];
						$attributeData = $attributeValue;
						$attributeValue = new KernelObject();
						foreach ($attributeCfg['Definitions'] as $definitionName) {
							$attributeValue->useDefinition($definitionName);
						}
						if($attributeID){
							$attributeValue->load($attributeID);	
						}else{
							$attributeValue->fromArray($attributeData);
						}
						
					}		
					break;
			}
		}
		$this->AttributeValues[$attributeName][] = $attributeValue;
	}
	
	public function setValue($attributeName, $attributeValue=null){
		
		$attributeCfg = $this->getAttribute($attributeName);
		
		if(is_array($attributeValue)){
			if($attributeCfg['IsList']){
				foreach($attributeValue as $itemValue){
					$this->addValue($attributeName, $itemValue);
				}
				return true;
			}
		}else{
			if(!$this->runValuePreProcessor($attributeName, $attributeValue)){
				return false;
			}	
		}
		
		if(!$attributeValue instanceof KernelObject){
			
			switch($attributeName){
				case 'ID':
				case 'Name':
				case 'Description':
				case 'Author':
				case 'Version':
					
					break;
				default:
					if(in_array('Object.String', $attributeCfg['Definitions']) || in_array('Object.Number', $attributeCfg['Definitions']) ||
					 		in_array('Object.Date', $attributeCfg['Definitions']) || in_array('Object.Boolean', $attributeCfg['Definitions'])){
						
					}else{
						if(!is_scalar($attributeValue)){
							if(is_array($attributeValue)){
								$attributeData = $attributeValue;
								$attributeValue = new KernelObject();
								if(array_key_exists('ID', $attributeValue)){
									$attributeID = $attributeData['ID'];
									$attributeValue->load($attributeID);	
								}else{
									$attributeValue->fromArray($attributeData);
								}
									
								
							}	
						}
					}		
					break;
			}
		}
		
		$this->AttributeValues[$attributeName] = $attributeValue;
		
		
		return true;
	}
	
	public function fromArray($inputData){
		
		if(!is_array($inputData)){
			$this->addError('Invalid Array Supplied', $inputData);
			return false;
		}
		
		//Definitions
		if(array_key_exists('Definitions', $inputData)){
			if(is_array($inputData['Definitions'])){
				foreach($inputData['Definitions'] as $definitionID){
					$this->useDefinition($definitionID);
				}	
			}
		}
		
		//Attributes
		if(array_key_exists('Attributes', $inputData)){
			if(is_array($inputData['Attributes'])){
				foreach($inputData['Attributes'] as $attributeName=>$attributeCfg){
					$definitions = null;
					$required = false;
					$isList = false;
					$isPrimitive = true;
					$label = $attributeName;
					if($attributeCfg){
						if(is_array($attributeCfg)){
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
	
							if(array_key_exists('Label', $attributeCfg)){
								$label = $attributeCfg['Label'];
							}
						}
					}
					
					$this->addAttribute($attributeName, $definitions, $required, $isList, $isPrimitive, $label);
				}	
			}
			
		}
		
		//Events
		if(array_key_exists('Events', $inputData)){
			if(is_array($inputData['Events'])){
				foreach($inputData['Events'] as $eventName=>$eventCfg){				
					$this->addEvent($eventName, $eventCfg);
				}	
			}
		}
				
		//attribute values
		if(array_key_exists('Data', $inputData)){
			if(is_array($inputData['Data'])){
				if(is_array($this->Attributes)){
					foreach($this->Attributes as $attributeName=>$attributeCfg){
						if(array_key_exists($attributeName, $inputData['Data'])){
							if(!is_null($inputData['Data'][$attributeName])){
								if($attributeCfg['IsList']==true){
									foreach($inputData['Data'][$attributeName] as $itemValue){
										$this->addValue($attributeName, $itemValue);
									}	
								}else{
									$this->setValue($attributeName, $inputData['Data'][$attributeName]);	
								}
							}
						}
					}
				}	
			}	
		}
		
		//actions
		
		
		if(array_key_exists('Actions', $inputData)){
			if(is_array($inputData['Actions'])){
				foreach($inputData['Actions'] as $eventName=>$action){
					foreach($action as $actionLabel=>$actionCfg){
						$actionObject = $this->loadAction($actionCfg['Name'], $actionCfg['Cfg']);
						$this->addAction($eventName, $actionLabel, $actionObject);
					}
				}	
			}
		}
		
		//permissions
		if(array_key_exists('Permissions', $inputData)){
			if(is_array($inputData['Permissions']) && count($inputData['Permissions'])>0){
				foreach($inputData['Permissions'] as $permissionsCfg){
					$permissionObject = new KernelObject('Object.Permission');
					$permissionObject->fromArray($permissionsCfg);
					$this->addPermission($permissionObject);
				}
			}
		}
	}
	
	public function toArray(){
		$returnArray = array();
		
		//definitions
		$returnArray['Definitions']=array();
		$definitionList = $this->getDefinitions();
		foreach($definitionList as $definitionName=>$definitionObject){
			$returnArray['Definitions'][] = $definitionName;
		}
		
		//attributes
		$returnArray['Attributes']=array();
		$returnArray['Data']=array();
		$attributeList = $this->getAttributes();
		if(is_array($attributeList)){
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
				
				$returnArray['Attributes'][$attributeName] = $attributeCfg;
				$attributeValue = $this->getValue($attributeName);
				
				if(!is_scalar($attributeValue)){
					if($attributeCfg['IsList']==true){
						$newValue = array();
						if(is_array($attributeValue)){
							foreach($attributeValue as $itemValue){
								if(!is_scalar($itemValue)){
									if($itemValue instanceof KernelObject){
										if(!$isPrimitive){
											$itemValue = array('ID'=>($itemValue->getValue('ID')));	
										}else{
											
											if($itemValue->getValue('ID')!=''){
												$itemValue = array('ID'=>($itemValue->getValue('ID')));
											}else{
												$itemValue = $itemValue->toArray();
											}	
										}
									}
								}
								$newValue[] = $itemValue;
							}
							$attributeValue = $newValue;	
						}
						
					}else{
						if($attributeValue instanceof KernelObject){
							if(!$isPrimitive){
								$attributeValue = array('ID'=>($attributeValue->getValue('ID')));	
							}else{
								
								if($attributeValue->getValue('ID')!=''){
									$attributeValue = array('ID'=>($attributeValue->getValue('ID')));
								}else{
									$attributeValue = $attributeValue->toArray();
								}	
							}
						}	
					}
					
				}
				
				$returnArray['Data'][$attributeName] = $attributeValue; 
			}	
		}
		
		//events
		$returnArray['Events']=array();
		$eventList = $this->getEvents();
		if(is_array($eventList)){
			$returnArray['Events']=array();
			foreach($eventList as $eventName=>$eventCfg){
				$returnArray['Events'][$eventName] = $eventCfg;
			}
		}
		
		//actions
		$returnArray['Actions']=array();
		$actionList = $this->getActions();
		if(is_array($actionList)){
			$returnArray['Actions']=array();
			foreach($actionList as $eventName=>$actions){
				foreach($actions as $actionLabel=>$action){
					$returnArray['Actions'][$eventName][$actionLabel] = $action->toArray();	
				}
				
			}
		}
		
		//permissions
		$returnArray['Permissions']=array();
		$permissionList = $this->getPermissions();
		if(is_array($permissionList)){
			foreach($permissionList as $permission){
				$returnArray['Permissions'][]=$permission->toArray();
			}
		}
		
		//extends
		$returnArray['Extends']=array();
		return $returnArray;
	}
	
	public function fromJSON($jsonString){
		$loaded = false;
		if($array = json_decode($jsonString)){
			if(!is_array($array)){
				$array = (array) $array;
			}
			$loaded = $this->fromArray($array);
		}
		return $loaded;
	}
	
	public function toJSON(){
		$returnString = $this->toArray();
		
		return json_encode($returnString);
	}
	
	public function createClone(){
		$cloneArray = $this->toArray();
		$cloneObject = new KernelObject();
		$cloneObject->fromArray($cloneArray);
		return $cloneObject;
	}
	
	public function merge($object, $map){
		if(!$this->fireEvent('BeforeMerge')){return false;}
		/*
		if(!($object instanceof KernelObject)){
			$this->addError('Merge Failed: Invalid Source Object', $object);
			return false;
		}
		
		$definitions = $object->getDefinitions();
		if(is_array($definitions)){
			foreach($definitions as $definitionName=>$definitionObject){
				if(!$this->usesDefinition($definitionName)){
					$this->addDefinition($definitionName);
				}
			}
		}
		
		$events = $object->getEvents();
		if(is_array($events)){
			foreach($events as $eventName=>$eventCfg){
				if(!$this->hasEvent($eventName)){
					$this->addEvent($eventName, $eventCfg);
				}
			}
		}
		
		$actions = $object->getActions();
		if(is_array($actions)){
			foreach($actions as $eventName=>$action){
				$this->addAction($eventName, $eventCfg);
			}
		}
		
		$attributes = $object->getAttributes();
		if(is_array($attributes)){
			foreach($attributes as $attributeName=>$attributeCfg){
				if(!$this->hasAttribute($attributeName)){
					$this->addAttribute($attributeName, $attributeCfg['Definitions'], $attributeCfg['Required'], $attributeCfg['IsList'], $attributeCfg['IsPrimitive'], $attributeCfg['Label']);
				}
				$this->setValue($attributeName, $this->getValue($attributeName));
			}
		}
		*/
		return $this->fireEvent('AfterMerge');
	}
	
	public function morph($object, $map){
		
	}
	
	public function suspendEvents(){
		$this->eventsSuspended = true;	
	}
	
	public function resumeEvents(){
		$this->eventsSuspended = false;
	}
	
	public function fireEvent($eventName){
		if($this->eventsSuspended){
			return true;
		}
		$continue = true;

		$actions = $this->getActions($eventName);
		
		if(is_array($actions)){
			foreach($actions as $actionLabel=>$action){
				if($continue){
					if($this->validateConditions($action->getValue('Conditions'))){
						$continue = $this->runAction($action);
						//echo ($continue==true?'done':'failed')."\n\n";
					}	
				}
			}
		}
		
		return $continue;
	}
	
	protected function validateConditions($Conditions){
		$valid = true;
		if(is_array($Conditions)){
			foreach ($Conditions as $Condition) {
				$attributeName = $Condition->getValue('Attribute');
				$operator = $Condition->getValue('Operator');
				$value = $Condition->getValue('Value');
			}
		}
		return $valid;
	}
	
	protected function beforeRun(&$inputObject){
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
				foreach($objectMap as $sourceAttribute=>$targetAttributes){
					
				}	
			}
			$continue = true;
		}
		
		if($continue){
			return $this->fireEvent('BeforeRun');
		}else{
			return false;
		}
	}
	
	protected function afterRun(&$inputObject){
		return $this->fireEvent('AfterRun');
	}
	
	protected function runAction($actionObject){
		//echo 'running action: '.$actionName.'<br/><Br/>';
		$actionRan = false;
		
		//$actionObject = $this->loadAction($actionName);
		if($actionObject){
			$actionRan = $actionObject->run($this);
		}
		
		
		return $actionRan;
	}

	public function loadAction($actionName, $actionCfg){
		if($actionName){
			//echo $actionName."\n";
			$actionObject = new KernelObject();
			if($actionObject->load($actionName) && $actionObject->getValue('FileName')!=''){
				//try and load the object from file
				
			}else{
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
				
				if(file_exists($fileString)){
					include_once($fileString);
					$className = str_replace('.', '', $actionName);
					if(class_exists($className)){
						$actionObject = new $className();	
						if($actionCfg){
							$actionObject->fromArray($actionCfg);	
						}
						return $actionObject;
					}else{
						$this->addError('Could not load Action: '.$actionName, $actionCfg);
						return false;
					}
				}
			}
		}
	}

	public function installCoreObjects(){
		foreach ($this->ReservedDefinitions as $definitionName) {
			$object = new KernelObject($definitionName);
			$object->setValue('ID', $definitionName);
			$object->setValue('Name', $definitionName);
			$object->setValue('Description', 'Core Object: '.$definitionName);
			$object->setValue('Author', 'Justin Pradier');
			$object->setValue('Version', '1.0.0');
			$object->useDefinition('Object.Definition');
			
			$object->save();
		}
		
		return true;
	}
}
?>
