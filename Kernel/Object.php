	<?php
/**
 * 
 * Base System Object
 * @author Justin Pradier <justin.pradier@fluxsingularity.com>
 * 
 */

class KernelObject{
	public static $_DataDriver=null;
	
	protected $_ObjectID=null;
	protected $_ObjectName=null;
	protected $_ObjectDescription=null;
	protected $_ObjectAuthor=null;
	protected $_ObjectVersion=null;
	protected $_ObjectSource=null;
	protected $_ObjectDestination=null;
	protected $_ObjectErrors=null;
	protected $_ObjectPermissions = null;
	
	protected $_ObjectTraceLevel = 0;
	protected $_ObjectTrace = array();
	
	protected $_ObjectEvents = array(
		array('Name'=>'AfterCreate', 'Source'=>'Kernel.Object'),
		array('Name'=>'BeforeSave', 'Source'=>'Kernel.Object'),
		array('Name'=>'AfterSave', 'Source'=>'Kernel.Object'),
		array('Name'=>'BeforeLoad', 'Source'=>'Kernel.Object'),
		array('Name'=>'AfterLoad', 'Source'=>'Kernel.Object'),
		array('Name'=>'BeforeRemove', 'Source'=>'Kernel.Object'),
		array('Name'=>'AfterRemove', 'Source'=>'Kernel.Object'),
		array('Name'=>'BeforeDefinitionAdd', 'Source'=>'Kernel.Object'),
		array('Name'=>'AfterDefinitionAdd', 'Source'=>'Kernel.Object'),
		array('Name'=>'BeforeFieldAdd', 'Source'=>'Kernel.Object'),
		array('Name'=>'AfterFieldAdd', 'Source'=>'Kernel.Object')
	);
	protected $_ObjectActions = null;
	
	protected $_ObjectDefinitions = array(
		'Kernel.Object'
	);
	
	protected $_ObjectFields = null;
	protected $_ObjectData = null;
	
	protected $_SuspendEvents = false;
	
	protected $numObjects = 0;
	
	protected $loadedDefinitions = array(
		'Kernel.Object',
		'Kernel.Definition',
		'Kernel.Event',
		'Kernel.Action',
		'Kernel.Query',
		'Kernel.Condition',
		'Kernel.Object.String',
		'Kernel.Object.Number',
		'Kernel.Object.Boolean',
		'Kernel.Object.Date',
		'Kernel.Module',
		'Kernel.Object.User',
		'Kernel.Object.Network',
		'Kernel.Object.Permission'
	);
	
	public static $_ReservedFields = array(
		'ID',
		'Name',
		'Definitions',
		'Description',
		'Version',
		'Author'
	);
	
	public static $_ReservedDefinitions = array(
		'Kernel.Object',
		'Kernel.Definition',
		'Kernel.Process',
		'Kernel.Event',
		'Kernel.Action',
		'Kernel.Query',
		'Kernel.Condition',
		'Kernel.Object.String',
		'Kernel.Object.Number',
		'Kernel.Object.Boolean',
		'Kernel.Object.Date',
		'Kernel.Module',
		'Kernel.Object.User',
		'Kernel.Object.Network',
		'Kernel.Object.Permission'
	);
	
	public function __construct($config=null){
		
		$this->addTrace('Kernel.Object', 'Creating Object');
		$this->_ObjectName = 'Kernel.Object';
		$this->_ObjectDescription = 'This is the base object that all other objects within Flux Singularity are based on';
		$this->_ObjectAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com>';
		$this->_ObjectVersion = '0.4.0';
		$this->_ObjectData = null;
		$this->_ObjectSource = null;
		$this->_ObjectDestination = null;
		
		if(is_scalar($config) && $config!='Kernel.Object'){
			$this->addDefinition($config);
		}else{
			if(is_array($config)){
				if(array_key_exists('Definition', $config)){
					if(is_array($config['Definition'])){
						foreach($config['Definition'] as $definitionName){
							if($definitionName!='Kernel.Object'){
								$this->addDefinition($definitionName);
							}	
						}
					}else{
						$definitionName = $config['Definition'];
						
						if($definitionName!='Kernel.Object'){
							$this->addDefinition($definitionName);
						}	
					}
				}
				
				if(array_key_exists('SuspendEvents', $config)){
					$this->_SuspendEvents = $config['SuspendEvents'];
				}
				
				if(array_key_exists('Data', $config)){
					$this->setValue($config['Data']);
				}
			}
		}
		
		$this->fireEvent('AfterCreate', $this);
		$this->addTrace('Kernel.Object', 'Creating Object');
	}
	
	public function getObjectID(){
		return $this->_ObjectID;
	}
	
	public function getObjectReference(){
		if(!$this->getObjectId()){
			$this->save();
		}
		return array('ID'=>$this->getObjectId(), 'Name'=>$this->getObjectName());
	}
	
	public function getObjectName(){
		return $this->_ObjectName;	
	}
	
	public function getObjectDescription(){
		return $this->_ObjectDescription;
	}
	
	public function getObjectActions(){
		return $this->_ObjectActions;	
	}
	
	public function getObjectAuthor(){
		return $this->_ObjectAuthor;
	}
	
	public function getObjectVersion(){
		return $this->_ObjectVersion;
	}
	
	public function getDefinitions(){
		return $this->_ObjectDefinitions;
	}
	
	public function getObjectDefinitions(){
		return $this->_ObjectDefinitions;
	}
	
	public function getObjectEvents(){
		return $this->_ObjectEvents;	
	}
	
	public function getObjectFields(){
		return $this->_ObjectFields;
	}
	
	public function setObjectId($id){
		$this->_ObjectID = $id;
	}
	
	public function setObjectName($name){
		$this->_ObjectName = $name;
	}
	
	public function setObjectDescription($description){
		$this->_ObjectDescription = $description;	
	}
	
	public function setObjectAuthor($author){
		$this->_ObjectAuthor = $author;
	}
	
	public function setObjectVersion($version){
		$this->_ObjectVersion = $version;
	}
	
	public function setObjectOwner($owner){
		
	}
	
	public function getObjectOwner(){
		
	}
	public function addPermission($permissionObject){
		$recipientFound = false;
		$newRecipient = $permissionObject->getValue('Recipient');
		if(is_array($this->_ObjectPermissions)){
			foreach($this->_ObjectPermissions as $idx=>$permission){
				$recipient = $permission->getValue('Recipient'); 
				if($recipient->getObjectId()==$newRecipient->getObjectId()){
					$this->_ObjectPermissions[$idx] = $permissionObject;
					$recipientFound = $idx;
				}
			}	
		}
		
		
		if(!$recipientFound){
			$this->_ObjectPermissions[] = $permissionObject;
		}else{
			$this->_ObjectPermissions[$idx] = $permissionObject;
		}
	}
	
	public function setObjectPermissions($permissionArray){
		$this->_ObjectPermissions = $permissionArray;
	}
	
	public function getObjectPermissions(){
		return $this->_ObjectPermissions;
	}
	
	public function clearObjectErrors(){
		$this->_ObjectErrors = null;
	}
	
	public function getObjectErrors(){
		return $this->_ObjectErrors;
	}
	
	public function addTrace($source, $message, $level=1){
		if($level <= $this->_ObjectTraceLevel){
			$this->_ObjectTrace[] = array('Source'=>$source, 'Message'=>$message, 'Level'=>$level);
		}
	}
	
	public function getObjectTrace(){
		return $this->_ObjectTrace;
	}
	
	public function addError($object){
		if(!is_array($this->_ObjectErrors)){
			$this->_ObjectErrors = array();
		}
		
		$this->_ObjectErrors[] = $object;
	}
	/**
	 * 
	 * Return the class Meta Information
	 */
	public function getClassMeta(){
		
		$meta = new stdClass();
		$meta->Name = $this->_ObjectName;
		$meta->Description = $this->_ObjectDescription;
		$meta->Author = $this->_ObjectAuthor;
		$meta->Version = $this->_ObjectVersion;
		
		return $meta;
	}
	
	public function fromJSON($document, $populateReferences=true){
		$this->addTrace($this->getObjectName(), 'Loading from JSON');
		$object = $this;
		
		if(is_object($document)){
			$document = (array) $document;
		}
		
		//have to add the definitions first so built in objects don't override
		if(array_key_exists('Definitions', $document)){
			foreach($document['Definitions'] as $definitionName){
				if($definitionName!='Kernel.Object'){
					$object->addDefinition($definitionName);
				}	
			}
		}
		if(array_key_exists('ID', $document)){
			$object->setObjectId($document['ID']);	
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
		
		$hasData = false;
		if(array_key_exists('Data', $document)){
			$hasData = true;	
		}
		
		if(array_key_exists('Fields', $document)){
			$this->addTrace($this->getObjectName(), 'Loading Field Definitions');
			foreach($document['Fields'] as $fieldCfg){
				if(is_object($fieldCfg)){
					$fieldCfg = (array) $fieldCfg;
				}
				$field = new KernelObjectFieldDefinition($fieldCfg);
				
				$object->addField($field);
				//$dataObject = $document['Data'];
				if($hasData && is_object($document['Data'])){
					$document['Data'] = (array) $document['Data'];
				}
				
				if($hasData && array_key_exists($fieldCfg['Name'], $document['Data'])){
					$fieldValue = $document['Data'][$fieldCfg['Name']];
					
					if($field->getIsList()){
						
						if(is_array($fieldValue)){
							foreach($fieldValue as $fieldValueItem){
								if($field->getIsPrimitive()){
									if(is_scalar($fieldValueItem)){
										$this->addValue($field->getName(), $fieldValueItem);	
									}else{
										$fieldValueItemObject = new KernelObject();
										$fieldValueItemObject->fromJSON($fieldValueItem);
										$this->addValue($fieldValueItemObject);
									}
									
								}else{
									if($populateReferences){
										$refObj = new KernelObject();
										$refObj->load($fieldValueItem['ID']);
										
										$this->addValue($field->getName(), $refObj);	
									}else{
										$this->addValue($field->getName(), $fieldValueItem);
									}
								}	
							}
						}else{
							if($field->getIsPrimitive()){
								$this->addValue($field->getName(), $fieldValue);
							}else{
								//MUST be an object reference
								$refObj = new KernelObject();
								$refObj->load($fieldValue['ID']);
								
								$this->addValue($field->getName(), $refObj);
							}	
						}
					}else{
						
						if($field->getIsPrimitive()){
							if(is_object($fieldValue)){
								$fieldValue = (array) $fieldValue;
								
								$fieldValueObject = new KernelObject();
								$fieldValueObject->fromJSON($fieldValue);
								//echo $field->getName()."\n\n";
								$this->setValue($field->getName(), $fieldValueObject);
							}else{
								$fieldObject = new KernelObject();
								$fieldObject->fromJSON($fieldValue);
								
								
								$this->setValue($field->getName(), $fieldValue);
							}
							
						}else{
							//MUST be an object reference
							$refObj = new KernelObject();
							$refObj->load($fieldValue['ID']);
							
							$this->setValue($field->getName(), $refObj);
						}
					}
				}
			}
		}
		
		if(array_key_exists('Events', $document)){
			$objectEvents = $document['Events'];
			if(is_array($objectEvents) && count($objectEvents)>0){
				foreach($objectEvents as $event){
					if(is_object($event)){
						$event = (array) $event;
					}
					$object->addEvent($event['Name'], $event['Source']);	
				}
			}	
		}
		
		if(array_key_exists('Actions', $document)){
			
			$objectActions = $document['Actions'];
			if(is_object($objectActions)){
				$objectActions = (array) $objectActions;
				
			}
			if(is_array($objectActions) && count($objectActions)>0){
				
				foreach($objectActions as $eventName=>$actions){
					foreach($actions as $action){
						if(is_object($action)){
							$action  = (array) $action;
						}
						$conditionList = array();
						if(is_array($action['Conditions'])){
							foreach($action['Conditions'] as $condition){
								if(is_object($condition)){
									$condition  = (array) $condition;
								}
								$conditionObject = new KernelObject();
								$conditionObject->fromJSON($condition);
								$conditionList[] = $conditionObject;
							}
						}
						$object->addAction($eventName, $action['ActionName'], $conditionList);	
					}	
				}
			}	
		}
		
		if(array_key_exists('Permissions', $document)){
			$permissions = $document['Permissions'];
			if(is_array($permissions)){
				foreach($permissions as $permissionCfg){
					if(is_object($permissionCfg)){
						$permissionCfg = (array) $permissionCfg;
					}
					$permissionObject = new KernelObject();
					$permissionObject->addDefinition('Kernel.Object.Permission');
					$permissionObject->setValue('Create', $permissionCfg['Create']);
					$permissionObject->setValue('Read', $permissionCfg['Read']);
					$permissionObject->setValue('Update', $permissionCfg['Update']);
					$permissionObject->setValue('Delete', $permissionCfg['Delete']);
					
					$permissionRecipient = $permissionCfg['Recipient'];
					
					if(is_object($permissionRecipient)){
						$permissionRecipient = (array) $permissionRecipient;
					}
					$userObject = new KernelObject();
					$userObject->load($permissionRecipient['ID']);
					
					$permissionObject->setValue('Recipient', $userObject);
					
					$object->addPermission($permissionObject);
				}		
			}
		}
		
		//return $object;
		return true;
	}
	
	public function toJSON($populateReferences=true, $includeData=true, $includeActions=true, $includeEvents=true, $includePermissions=true){
		
		$this->addTrace('Kernel.Object', 'Converting to JSON');
		$this->addTrace('Kernel.Object', '--Populate References: '.($populateReferences?'Yes':'No'));
		$object = $this;
		
		if($object instanceof KernelObject){
    		$definitionName = $object->getObjectName();
			
			$objectId = $object->getObjectId();
			$objectName = $object->getObjectName();
			$objectDescription = $object->getObjectDescription();
			$objectAuthor = $object->getObjectAuthor();
			$objectVersion = $object->getObjectVersion();
			
			$objectActions = $object->getObjectActions();
			$objectDefinitions = $object->getObjectDefinitions();
			$objectFields = $object->getObjectFields();
			$objectEvents = $object->getObjectEvents();
			$objectPermissions = $object->getObjectPermissions();
			
			$objectData = array();
			
			$definitions = $object->getDefinitions();
			
			$saveData = array(
				'Name'=>$objectName,
				'Description'=>$objectDescription,
				'Author'=>$objectAuthor,
				'Version'=>$objectVersion
			);
			
			if($objectId){
				$saveData['ID'] = $objectId;
			}
			
			if($includeActions && is_array($objectActions)){
				if(!array_key_exists('Actions', $saveData)){
					$saveData['Actions'] = array();
				}
				
				foreach($objectActions as $eventName=>$actions){
					
					if(!array_key_exists($eventName, $saveData['Actions'])){
						$saveData['Actions'] = array();
					}
					foreach($objectActions[$eventName] as $actionName=>$actionCfg){
						$conditions=false;
						if(is_array($actionCfg) && array_key_exists('Conditions', $actionCfg)){
							$conditions = $actionCfg['Conditions'];
						}
						$fieldMap = false;
						if(is_array($actionCfg) && array_key_exists('FieldMap', $actionCfg)){
							$fieldMap = $actionCfg['FieldMap'];
						}
						
						$conditionList = array();
						if(is_array($conditions)){
							foreach($conditions as $condition){
								if($condition instanceof KernelObject){
									$conditionList[] = $condition->toJSON();
								}
							}	
						}
						
						
						$saveData['Actions'][$eventName][] = array('ActionName'=>$actionName, 'Conditions'=>$conditionList, 'FieldMap'=>$fieldMap);
					}
				}	
			}
			
			if(is_array($objectDefinitions) && count($objectDefinitions)>0){
				$saveData['Definitions'] = $objectDefinitions;
			}
			
			if($includeEvents && is_array($objectEvents) && count($objectEvents)>0){
				$saveData['Events'] = $objectEvents;
			}
			
			if(is_array($objectFields) && count($objectFields)>0){
				$fieldData = array();
				
				foreach($objectFields as $fieldName=>$fieldCfg){
					
					
					$fieldData[] = array(
						'Name'=>$fieldCfg->getName(),
						'Definition'=>$fieldCfg->getDefinitionName(),
						'Required'=>$fieldCfg->getRequired(),
						'IsList'=>$fieldCfg->getIsList(),
						'IsPrimitive'=>$fieldCfg->getIsPrimitive()
					);
					
					$fieldValue = $object->getValue($fieldName);
					
					if($fieldCfg->getIsList()){	
						if(is_array($fieldValue)){
							$objectData[$fieldName] = array();
							
							foreach($fieldValue as $valueItem){
								if($fieldCfg->getIsPrimitive()){
									if($valueItem instanceof KernelObject){
										if($fieldCfg->getIsPrimitive()){
											$objectData[$fieldName][] = $valueItem->toJSON($populateReferences);	
										}else{
											$objectData[$fieldName][] = $valueItem->getObjectReference();
										}	
									}else{
										$objectData[$fieldName][] = $valueItem;
									}
								}else{
									if($valueItem instanceof KernelObject){
										
										if($fieldCfg->getIsPrimitive()){
											$objectData[$fieldName][] = $valueItem->toJSON($populateReferences);	
										}else{
											$objectData[$fieldName][] = $valueItem->getObjectReference();
										}
									}else{
										$objectData[$fieldName][] = $valueItem;
									}
								}
										
							}
						}
					}else{
						if(!is_null($fieldValue)){
							if($fieldCfg->getIsPrimitive()){
								if($fieldValue instanceof KernelObject){
									$objectData[$fieldName] = $fieldValue->toJSON($populateReferences);
								}else{
									$objectData[$fieldName] = $fieldValue;
								}
							}else{
								if($fieldValue instanceof KernelObject){
									if($populateReferences===true){
										$objectData[$fieldName] = $fieldValue->toJSON($populateReferences);
									}else{
										$objectData[$fieldName] = $fieldValue->getObjectReference();
									}
								}else{
									$objectData[$fieldName] = $fieldValue;
								}
							}
								
						}
					}
				}
				$saveData['Fields'] = $fieldData;
				
				if(count($objectData)>0){
					$saveData['Data'] = $objectData;
				}
			}

			if($includePermissions && count($objectPermissions)){
				$saveData['Permissions'] = array();
				foreach($objectPermissions as $permission){
					$permissionData = array(
						'Recipient'=>null,
						'Create'=>$permission->getValue('Create'),
						'Read'=>$permission->getValue('Read'),
						'Update'=>$permission->getValue('Update'),
						'Delete'=>$permission->getValue('Delete')
					);
					
					$recipient = $permission->getValue('Recipient');
					
					if($recipient){
						$permissionData['Recipient'] = $recipient->getObjectReference();
					}
					
					$saveData['Permissions'][] = $permissionData;
				}
			}
			
			if(count($object->getObjectErrors())>0){
				$saveData['Errors']=array();
				foreach($object->getObjectErrors() as $error){
					$saveData['Errors'][]=$error->toJSON();
				}
			}
			
			$traceItems = $this->getObjectTrace();
			
			if(count($traceItems)>0){
				$saveData['Trace'] = $traceItems;
			}
			
			return $saveData;
    	}
	}
	
	
	public function addDefinition($definitionName){
		if(!$this->fireEvent('BeforeDefinitionAdd', $this)){return false;}
		
		if(!$this->dbAvailable()){
			return false;
		}
		
		if($this->hasDefinition($definitionName)){
			return true;
		}

		$definitions = array();
		if($definitionName!='Kernel.Query' && $definitionName!='Kernel.Condition'){
			$queryObject = new KernelObject(array('SuspendEvents'=>true));
			$queryObject->addDefinition('Kernel.Query');
			$queryObject->setValue('Type', 'AND');
			
			$definitionCondition = new KernelObject('Kernel.Condition');
			$definitionCondition->setValue('FieldName', 'Definitions');
			$definitionCondition->setValue('Operator', '==');
			$definitionCondition->setValue('Value', 'Kernel.Definition');
			
			$definitionNameCondition = new KernelObject('Kernel.Condition');
			$definitionNameCondition->setValue('FieldName', 'Name');
			$definitionNameCondition->setValue('Operator', '==');
			$definitionNameCondition->setValue('Value', $definitionName);
			
			$queryObject->addValue('Conditions', $definitionCondition);
			$queryObject->addValue('Conditions', $definitionNameCondition);
			
			$definitions = self::$_DataDriver->find($queryObject);
		}
		
		
		if(count($definitions)>0){
			foreach($definitions as $definition){
				
				if($definition->getObjectName()!='Kernel.Definition'){
					$definitionItemName = $definition->getObjectName() ;
					if($definitionItemName!=$this->getObjectName()){
						$this->_ObjectDefinitions[] = $definitionName;
					}
					$definition->removeDefinition('Kernel.Definition');
					//$definition->removeDefinition($definitionName);
					$definition = $definition->toJSON();
					unset($definition['ID']);
					unset($definition['Name']);
					unset($definition['Description']);
					unset($definition['Author']);
					unset($definition['Version']);
					
					$this->fromJSON($definition);
				}
			}
		}else{
			if(in_array($definitionName, self::$_ReservedDefinitions)){
				if($definitionName!=$this->getObjectName()){
					$this->_ObjectDefinitions[] = $definitionName;
				}
				
				$prevSuspendSetting = $this->_SuspendEvents;
				$this->_SuspendEvents = true;
				switch($definitionName){
					case 'Kernel.Process':
						
						break;
					case 'Kernel.Definition':
						//if($this->getObjectName())
						//$this->setObjectName('Kernel.Definition');
						//$this->setObjectDescription('Object Definition');
						//$this->setObjectVersion('1.0.0');
						//$this->setObjectAuthor('Justin Pradier');
						break;
					case 'Kernel.Event':
						$this->setObjectName('Kernel.Event');
						$this->setObjectDescription('Object Event Definition');
						$this->setObjectVersion('1.0.0');
						$this->setObjectAuthor('Justin Pradier');
						
						$this->addField('Event', 'Kernel.Object.String', true, false);
						$this->addField('TargetDefinition', 'Kernel.Object.String', true, false);
						$this->addField('Conditions', 'Kernel.Object', false, true);
						$this->addField('Actions', 'Kernel.Object.String', true, true);
						break;
					case 'Kernel.Action':
						$this->setObjectName('Kernel.Action');
						$this->setObjectDescription('Action Definition');
						$this->setObjectVersion('1.0.0');
						$this->setObjectAuthor('Justin Pradier');
						
						$this->addField('Status', 'Kernel.Object.String', true, false);
						
						$this->addEvent('BeforeRun', 'Kernel.Action');
						$this->addEvent('AfterRun', 'Kernel.Action');
						break;
					case 'Kernel.Query':
						$this->setObjectName('Kernel.Query');
						$this->setObjectDescription('Query Definition');
						$this->setObjectVersion('1.0.0');
						$this->setObjectAuthor('Justin Pradier');
						$this->addField('Type', 'Kernel.Object.String');
						$this->setValue('Type', 'AND');
						$this->addField('Conditions', 'Kernel.Condition', false, true);
						$this->addField('Results', 'Kernel.Object', false, true);
						break;
					case 'Kernel.Condition':
						$this->setObjectName('Kernel.Condition');
						$this->setObjectDescription('Condition Definition');
						$this->setObjectVersion('1.0.0');
						$this->setObjectAuthor('Justin Pradier');
						
						$this->addField('FieldName', 'Kernel.Object.String');
						$this->addField('Operator', 'Kernel.Object.String');
						$this->addField('Value', 'Kernel.Object', false, true);
						break;
					case 'Kernel.Object.String':
						$this->setObjectName('Kernel.Object.String');
						$this->setObjectDescription('Basic String Object');
						$this->setObjectVersion('1.0.0');
						$this->setObjectAuthor('Justin Pradier');
						break;
					case 'Kernel.Object.Number':
						$this->setObjectName('Kernel.Object.Number');
						$this->setObjectDescription('Basic Number Object');
						$this->setObjectVersion('1.0.0');
						$this->setObjectAuthor('Justin Pradier');
						break;
					case 'Kernel.Object.Boolean':
						$this->setObjectName('Kernel.Object.Boolean');
						$this->setObjectDescription('Basic Boolean Object');
						$this->setObjectVersion('1.0.0');
						$this->setObjectAuthor('Justin Pradier');
						break;
					case 'Kernel.Object.Date':
						$this->setObjectName('Kernel.Object.Date');
						$this->setObjectDescription('Basic Date and Time Object');
						$this->setObjectVersion('1.0.0');
						$this->setObjectAuthor('Justin Pradier');
						break;
					case 'Kernel.Module':
						$this->setObjectName('Kernel.Module');
						$this->setObjectDescription('System Module Definition');
						$this->setObjectVersion('1.0.0');
						$this->setObjectAuthor('Justin Pradier');
						
						$this->addField('ActionList', 'Kernel.Object.String', false, true);
						$this->addField('DefinitionList', 'Kernel.Object.String', false, true);
						break;
					case 'Kernel.Object.User':
						$this->setObjectName('Kernel.Object.User');
						$this->setObjectDescription('Security User Object');
						$this->setObjectVersion('1.0.0');
						$this->setObjectAuthor('Justin Pradier');
						
						$this->addField('Username', 'Kernel.Object.String', true, false);
						$this->addField('Password', 'Kernel.Object.String', true, false);
						$this->addField('DisplayName', 'Kernel.Object.String', true, false, 'Display Name');
						$this->addField('Networks', 'Kernel.Object.Network', false, true);
						
						$this->addEvent('LoggedIn', 'Kernel.Object.User');
						$this->addEvent('LoggedOut', 'Kernel.Object.User');
						break;
					case 'Kernel.Object.Network':
						$this->setObjectName('Kernel.Object.Network');
						$this->setObjectDescription('Basic User Network used for Security');
						$this->setObjectVersion('1.0.0');
						$this->setObjectAuthor('Justin Pradier');
						
						$this->addField('Users', 'Kernel.Object.User', false, true);
						$this->addField('Networks', 'Kernel.Object.Network', false, true);
						break;
					case 'Kernel.Object.Permission':
						$this->setObjectName('Kernel.Object.Permission');
						$this->setObjectDescription('Basic Object Permission Object');
						$this->setObjectVersion('1.0.0');
						$this->setObjectAuthor('Justin Pradier');
						
						$this->addField('Create', 'KernelObject.Boolean');
						$this->addField('Read', 'KernelObject.Boolean');
						$this->addField('Update', 'KernelObject.Boolean');
						$this->addField('Delete', 'KernelObject.Boolean');
						
						$this->addField('Recipient', 'Kernel.Object.User', true, false, false);
						
						break;
					case 'Kernel.Object.Task':
						$this->setObjectName('Kernel.Object.Task');
						$this->setObjectDescription('Basic Task Object');
						$this->setObjectVersion('1.0.0');
						$this->setObjectAuthor('Justin Pradier');
						$this->addField('Action', 'Kernel.Object.String', true);
						break;

				}
				
				$this->_SuspendEvents = $prevSuspendSetting;
			}else{
				return false;	
			}
			if(!$this->fireEvent('AfterDefinitionAdd', $this)){return false;}
		}
	}
	
	public function removeDefinition($definitionName){
		if(!$this->fireEvent('BeforeDefinitionRemove', $this)){return false;}
		foreach($this->_ObjectDefinitions as $definitionIdx=>$definition){
			
			if($definition==$definitionName){
				unset($this->_ObjectDefinitions[$definitionIdx]);
			}
		}
		if(!$this->fireEvent('AfterDefinitionRemove', $this)){return false;}
	}
	
	public function hasDefinition($definitionName){
		return in_array($definitionName, $this->_ObjectDefinitions);
	}
	
	public function addField($fieldName, $fieldDefinition='Kernel.Object', $fieldRequired=false, $fieldIsList=false, $fieldIsPrimitive=true, $fieldLabel = false){
		if(!$this->fireEvent('BeforeFieldAdd', $this)){return false;}
		if(!is_array($this->_ObjectFields)){
			$this->_ObjectFields = array();
		}
		
		if($fieldName instanceof KernelObjectFieldDefinition){
			if(!array_key_exists($fieldName->getName(), $this->_ObjectFields)){
				$this->_ObjectFields[$fieldName->getName()] = $fieldName;
			}else{
				return false;
			}
		}else{
			if(!array_key_exists($fieldName, $this->_ObjectFields)){
				$newField = new KernelObjectFieldDefinition(
					array(
						'Name'=>$fieldName,
						'Definition'=>$fieldDefinition,
						'Required'=>$fieldRequired,
						'IsList'=>$fieldIsList,
						'Label'=>$fieldLabel,
						'IsPrimitive'=>$fieldIsPrimitive
					)
				);
				
				$this->_ObjectFields[$fieldName] = $newField;
			}else{
				return false;
			}
		}
		return $this->fireEvent('AfterFieldAdd', $this);
	}
	
	public function getField($fieldName){
		if($this->hasField($fieldName)){
			return $this->_ObjectFields[$fieldName];
		}else{
			return false;
		}
	}
	
	public function removeField($fieldName){
		if(!$this->fireEvent('BeforeFieldRemove', $this)){return false;}
		unset($this->_ObjectFields[$fieldName]);
		unset($this->_ObjectData[$fieldName]);
		if(!$this->fireEvent('AfterFieldRemove', $this)){return false;}
	}
	
	public function hasField($fieldName){
		
		if(!is_array($fieldName)){
			if(!is_null($this->_ObjectFields)){
				return array_key_exists($fieldName, $this->_ObjectFields);	
			}else{
				return false;
			}
			
		}else{
			return true;
		}
	}
	
	protected function setFieldValue($fieldName, $fieldValue){
		
		//if(!$this->fireEvent('BeforeDataChanged', $this)){return false;}
		$fieldDef = $this->_ObjectFields[$fieldName];
		
		if(!is_array($this->_ObjectData)){
			$this->_ObjectData = array();
		}
		
		if(array_key_exists($fieldName, $this->_ObjectData)){
			//do we backup the current value???
			$currentValue = $this->_ObjectData[$fieldName];
		}
		
		$this->_ObjectData[$fieldName] = $fieldValue;
		//if(!$this->fireEvent('AfterDataChanged', $this)){return false;}
	}
	
	public function getValue($fieldName=null){
		$returnData = null;
		if(!isset($fieldName)){
			$returnData = $this->_ObjectData;
		}else{
			if($this->hasField($fieldName)){
				
				$fieldCfg = $this->_ObjectFields[$fieldName];
				$fieldDefinitions = $fieldCfg->getDefinitionName();
				$fieldIsList = $fieldCfg->getIsList();
				$fieldIsPrimitive = $fieldCfg->getIsPrimitive();
				if(is_scalar($this->_ObjectData)){
					$returnData = $this->_ObjectData;
				}else{
					if(is_array($this->_ObjectData)){
						if(array_key_exists($fieldName, $this->_ObjectData)){
							$returnData = $this->_ObjectData[$fieldName];
							
							if(!is_scalar($returnData)){
								if($this->getObjectName()=='asdfadsfadsf' && $fieldName=='Conditions'){
									//TODO: NEED TO DEAL WITH AN ERROR WITH LOADING CONDITION VALUES 
								}
								if($fieldIsList){
									$returnObject = array();
									
									foreach($returnData as $itemData){
										
										if(is_scalar($itemData)){
											$returnObject[] = $itemData;
										}else{
											if($itemData instanceof KernelObject){
												$returnObject[] = $itemData;
											}else{
												if($fieldIsPrimitive){
													$itemObject = new KernelObject();
													if($fieldIsPrimitive){
														$itemObject->fromJSON($itemObject);	
													}else{
														$itemObject->load($itemObject['ID']);
													}	
												}else{
													$itemObject = $itemData;
												}
												
												$returnObject[] = $itemObject;
											}
										}
									}
									
									$returnData = $returnObject;
								}else{
									if(!$returnData instanceof KernelObject){
										$returnObject = new KernelObject();
										$returnObject->fromJSON($returnData);
										$returnData = $returnObject;	
									}
								}
							}
						}	
					}
				}
			}
		}
		
		return $returnData;
	}
	
	public function setValue($fieldNameOrObjectValue, $fieldValue=null){
		$this->addTrace($this->getObjectName(), 'Setting Value');
		if($fieldValue instanceof DateTime){
			$fieldValue = $fieldValue->format(DATE_W3C);
		}
		
		if(isset($fieldValue)){
			if(!$this->hasField($fieldNameOrObjectValue)){
				
				$definitionName = $this->determineValueDefinition($fieldValue);
				
				if($definitionName){	
					if(!$this->addField($fieldNameOrObjectValue, $definitionName)){
						return false;
					}
				}else{
					return false;
				}
			}
			
			$fieldCfg = $this->getField($fieldNameOrObjectValue);
			
			if($fieldCfg->getIsPrimitive()){
				
				if($fieldValue instanceof KernelObject){
					$newFieldValue = $fieldValue;//->toJSON();
				}else{
					$newFieldValue = $fieldValue;
				}
			}else{
				if($fieldValue instanceof KernelObject){
					$newFieldValue = $fieldValue;//->getObjectReference();
				}else{
					$newFieldValue = $fieldValue;
				}	
			}
			
			
			return $this->setFieldValue($fieldNameOrObjectValue, $newFieldValue);	
		}else{
			if(is_scalar($fieldNameOrObjectValue)){
					$this->_ObjectData = $fieldNameOrObjectValue;
				}else{
					if($fieldNameOrObjectValue instanceof KernelObject){
						$this->_ObjectData = $fieldNameOrObjectValue->getValue();
						return true;
					}else{	
						if(is_array($fieldNameOrObjectValue)){
							foreach($fieldNameOrObjectValue as $fieldName=>$fieldValue){
								$this->setValue($fieldName, $fieldValue);
							}
						}
						return false;
					}	
				}
				
		}
	}
	
	public function addValue($fieldNameOrObjectValue, $fieldValue=null){
		$this->addTrace($this->getObjectName(), 'Adding Value');
		if(!isset($fieldValue)){
			if(is_scalar($fieldNameOrObjectValue)){
				if(!is_array($this->_ObjectData)){
					$this->_ObjectData = array();
				}
				
				$this->_ObjectData[] = $fieldValue;
				return true;
			}else{
				return false;
			}
		}else{
			$fieldCfg = $this->_ObjectFields[$fieldNameOrObjectValue];
			if($fieldCfg->getIsList()){
				$currentValues = $this->getValue($fieldNameOrObjectValue);
				
				if(!$currentValues){
					$currentValues = array();
				}
				
				if(is_scalar($fieldValue)){
					$currentValues[] = $fieldValue;
				}else{
					if($fieldValue instanceof KernelObject){
						$saveValue = $fieldValue;
						$currentValues[] = $saveValue;
					}
				}
				
				$this->setValue($fieldNameOrObjectValue, $currentValues);
				return true;
			}else{
				return false;
			}
		}
		
	}
	
	public function hasEvent($eventName){
		$events = $this->_ObjectEvents;
		$eventFound = false;
		foreach($events as $eventIdx=>$eventCfg){
			if($eventCfg['Name']==$eventName){
				$eventFound = true;
				break;
			}
		}
		return $eventFound;
	}
	
	public function addEvent($eventName, $sourceObject=false){
		$this->addTrace('Kernel.Object::addEvent', 'Adding Event: '.$eventName);
		if(!$sourceObject){
			$sourceObject = $this->getObjectName();
		}
		
		if(!$this->hasEvent($eventName)){
			$this->_ObjectEvents[] = array('Name'=>$eventName, 'Source'=>$sourceObject);
		}
		return true;
	}
	
	public function addAction($eventName, $actionName, $conditions=null, $mapping = null){
		
		if(!is_array($this->_ObjectActions)){
			$this->_ObjectActions = array();
		}
		
		if(!array_key_exists($eventName, $this->_ObjectActions)){
			$this->_ObjectActions[$eventName] = array();
		}
		
		if(array_key_exists($eventName, $this->_ObjectActions)){
			$events = $this->_ObjectActions[$eventName];
			if(!array_key_exists($actionName, $events)){
				$events[$actionName] = array();
				$conditionList = array();
				$mapList = array();
				
				if(is_array($conditions)){
					foreach($conditions as $condition){
						if($condition instanceof KernelObject){
							$conditionList[] = $condition;
						}else{
							if(is_array($condition)){
								$newCondition = new KernelObject('Kernel.Condition');
								$newCondition->setValue('FieldName', $condition['FieldName']);
								$newCondition->setValue('Operator', $condition['Operator']);
								$newCondition->setValue('Value', $condition['Value']);
								$conditionList[] = $condition;
							}
						}
					}
				}
				
				if(is_array($mapping)){
					$mapList = $mapping;
				}
				
				$events[$actionName][] = array('Conditions'=>$conditionList, 'FieldMap'=>$mapList);
				
				$this->_ObjectActions[$eventName] = $events;
				return true;
			}
		}
		
		return false;
	}
	
	public static function determineValueDefinition($value){
		$definition = 'Kernel.Object';
		
		if(is_scalar($value)){
			$definition = 'Kernel.Object.String';
			
			if(is_bool($value)){
				$definition = 'Kernel.Object.Boolean';
			}
			
			if(is_numeric($value)){
				$definition = 'Kernel.Object.Number';
			}
		}else{
			if(is_array($value)){
				$definition = 'Kernel.Object';
			}
			
			if(is_object($value)){
				if($value instanceof KernelObject){
					
					//$definition = $value->getObjectName();
					$definition = 'Kernel.Object';
				}else{
					//what the hell do i do here??
					//print_r('Need to deal with determineField Defintion handling of Objects that aren\'t KernelObjects');
					return false;
				}
			}
		}
		
		return $definition;
	}
	
	public function dbAvailable(){
		if(self::$_DataDriver){
			return self::$_DataDriver->isConnected();
		}else{
			return false;
		}
	}
	
	public function validate(){
		$this->addTrace($this->getObjectName(), 'Validating Object');
		$fieldDefinitions = $this->getObjectFields();
		$errored = false;
		
		if($this->hasDefinition('Kernel.Definition')){//validate as a definition
			$this->addTrace($this->getObjectName(), ' - Validating Definition');
		
			if(count($this->_ObjectFields)==0 && $this->getObjectName()!='Kernel.Object'){
				$error = new KernelObject(array('SuspendEvents'=>true));
				$error->setObjectName('No Fields Supplied');
				$error->setObjectDescription('No fields were supplied');
				$this->addError($error);
				$errored = true;
			}
		}else{
			foreach($fieldDefinitions as $fieldName=>$fieldDef){
				$fieldValue = $this->getValue($fieldName);
				$errors = $fieldDef->validateData($fieldValue);
				if($errors){
					foreach($errors as $error){
						$this->addError($error);
					}
					$errored = true;
				}
			}	
		}
		
		return !$errored;
	}
	
	
	public function save(){
		if(!$this->fireEvent('BeforeSave', $this)){return false;}
		
		if(!$this->dbAvailable()){
			return false;
		}
		
		//return false if the object does pass validation
		if(!$this->validate()){
			return false;
		}
		
		if(self::$_DataDriver->save($this)){
			return true;
		}else{
			return false;
		}
		
		return $this->fireEvent('AfterSave', $this);
	}
	
	public function load($id){
		if(!$this->fireEvent('BeforeLoad', $this)){return false;}
		
		if(!$this->dbAvailable()){
			return false;
		}
		
		if(!$id){
			return false;
		}else{
			$this->setObjectId($id);
			
			if(!self::$_DataDriver->loadById($this)){
				return false;
			}
		}
		
		return $this->fireEvent('AfterLoad', $this);
	}
	
	//query support
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
	 * 
	 * OR
	 * AND
	 * 
	 */
	public function find(){
		$returnObject = self::$_DataDriver->find($this);
		
		if($this->hasField('Definitions')){
			$outputDefs = false;
			$outputActions = false;
			$definitions = $this->getValue('Definitions');
			
			if(is_array($definitions)){
				foreach ($definitions as $definition) {
					if($definition=='Kernel.Definition'){
						$outputDefs = true;
					}
					if($definition=='Kernel.Action'){
						$outputActions = true;
					}
				}
			}else{
				if($definitions=='Kernel.Definition'){
					$outputDefs = true;
				}	
			}
			if($outputDefs){
				$results = $this->getValue('Results');
				//need to create entries for the built in definitions
				$def = new KernelObject();
				$def->addDefinition('Kernel.Object.String');
				$results[] = $def;
				
				$def = new KernelObject();
				$def->addDefinition('Kernel.Object.Number');
				$results[] = $def;
				
				$def = new KernelObject();
				$def->addDefinition('Kernel.Object.Boolean');
				$results[] = $def;
				
				$def = new KernelObject();
				$def->addDefinition('Kernel.Object.Date');
				$results[] = $def;
				
			}
			if($outputActions){
				
			}
		}
		
		return $returnObject;
	}
	
	public function findOne(){
		return self::$_DataDriver->findOne($this);
	}
	
	public function remove(){
		if(!$this->fireEvent('BeforeRemove', $object)){return false;}
		
		if(!$this->dbAvailable()){
			return false;
		}
		
		if(!self::$_DataDriver->remove($this)){
			return false;
		}
		
		return $this->fireEvent('AfterRemove', $object);
	}
	
	public function fireEvent($eventName, $object){
		
		$this->addTrace($this->getObjectName(), 'Firing Event: '.$eventName);
		$continue = true;
		
		if(!$this->_SuspendEvents){
			
			if(!$this->dbAvailable()){
				return true;
			}
			//if this object has any built in events, run them first
			if(is_array($this->_ObjectActions)){
				//print_r($this->_ObjectActions);
				if(array_key_exists($eventName, $this->_ObjectActions)){
					
					$this->addTrace($this->getObjectName(), count($this->_ObjectActions[$eventName]).' Actions found');
					foreach($this->_ObjectActions[$eventName] as $actionName=>$actionCfg){
						$this->addTrace($this->getObjectName(), 'Running Action: '.$actionName);
						if(!$this->runAction($actionName)){
							$continue = false;
							break;
						}
					}	
				}else{
					$this->addTrace($this->getObjectName(), 'No Actions found for this Event');
				}
			}else{
				$this->addTrace($this->getObjectName(), 'No Actions found for this Event');
			}
			
			//load any events for this supplied event and object combination
			$definitions = $this->getDefinitions();
			if($continue){
				/*foreach($definitions as $definitionName){
				if(!$this->fireDefinitionEvent($definitionName, $eventName, $object)){
					$continue = false;
					break;
				}
			}	*/
			}
			
		}else{
			$this->addTrace($this->getObjectName(), 'Events are Suspended for this Object');
		}
		
		return $continue;
		
	}
	
	public function fireDefinitionEvent($definitionName='Kernel.Object', $eventName, &$object){
		
		$query = new KernelObject(array('SuspendEvents'=>true));
		
		$query->setValue('Event', $eventName);
		$query->setValue('TargetObjectDefinition', $definitionName);
		
		$events = $query->find();
		
		if(count($events)>0){
			foreach($events as $event){
				$actions = $event->getValue('Actions');
				if($actions){
					foreach($actions as $action){
						$this->runAction($action);
					}	
				}
			}
		}
		return true;
	}
	
	public function runAction($actionName){
		$fsk = Kernel::singleton();
		return $fsk->runAction($actionName, $this);
	}
	
	public function suspendEvents(){
		$this->_SuspendEvents = true;
	}
	
	public function resumeEvents(){
		$this->_SuspendEvents = false;
	}
}
?>