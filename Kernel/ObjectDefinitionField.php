<?php 
class KernelObjectFieldDefinition {//extends KernelObject{
	
	private $_fieldName = null;
	private $_fieldDefinition = 'Kernel.Object.String';
	private $_required = false;
	private $_allowList = false;
	private $_label = null;
	private $_primitive = false;
	
	public static $_ReservedDefinitions = array(
		'Kernel.Object.String',
		'Kernel.Object.Number',
		'Kernel.Object.Boolean',
		'Kernel.Object.Date'
	);
	
	public function __construct($config=null){
		//parent::__construct($config);
		
		$this->_ClassName = 'Kernel.Object.Definition.Field';
		$this->_ClassTitle = 'Kernel Object Definition Field';
		$this->_ClassDescription = 'Kernel Base Definition Field Object';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com>';
		$this->_ClassVersion = '0.4.0';
		
		if(is_array($config)){
			$this->loadConfigArray($config);
		}
	}
	
	protected function loadConfigArray($config){
		
		if(array_key_exists('Name', $config)){
			$this->setName($config['Name']);
		}
		
		if(array_key_exists('Definition', $config)){
			$this->setDefinitionName($config['Definition']);
		}
		
		if(array_key_exists('Required', $config)){
			$this->setRequired($config['Required']);
		}
		
		if(array_key_exists('IsList', $config)){
			$this->setIsList($config['IsList']);
		}
		
		if(array_key_exists('Label', $config)){
			$this->setLabel($config['Label']);
		}
		
		if(array_key_exists('IsPrimitive', $config)){
			$this->setIsPrimitive($config['IsPrimitive']);
		}
	}
	
	/*
	 * Core Value Setter and Getter Functions
	 */
	
	/*
	 * Sets the value of the Field Name
	 */
	public function setName($name){
		$this->_fieldName = $name;
	}
	
	/*
	 * Returns the value of the Field Name
	 */
	public function getName(){
		return $this->_fieldName;
	}
	
	/*
	 * Sets the value of the Field Definition
	 */
	public function setDefinitionName($name){
		$this->_fieldDefinition = $name;
	}
	
	/*
	 * Returns the value of the Field Definition
	 */
	public function getDefinitionName(){
		return $this->_fieldDefinition;
	}
	
	/*
	 * Sets the value of the Required flag
	 */
	public function setRequired($value){
		$this->_required = $value;	
	}
	
	/*
	 * Gets the value of the Required Flag
	 */
	public function getRequired(){
		return $this->_required;
	}
	
	/*
	 * Sets the value of the List flag
	 */
	public function setIsList($value){
		$this->_allowList = $value;	
	}
	
	/*
	 * Gets the value of the List Flag
	 */
	public function getIsList(){
		return $this->_allowList;
	}
	
	public function setIsPrimitive($primitive){
		$this->_primitive=$primitive;
	} 
	
	
	public function getIsPrimitive(){
		return $this->_primitive;
	}
	
	/*
	 * Sets the value of the Field Label
	 */
	public function setLabel($label){
		$this->_label = $label;
	}
	
	/*
	 * Gets the value of the Field Label
	 */
	public function getLabel(){
		return $this->_label;
	}
	
	public function validateData($data){
		$errors = array();
		
		//required?
		if($this->getRequired()){
			if(!isset($data)){
				$error = new KernelObject();
				$error->setObjectName('Field Required');
				$error->setObjectDescription('Field '.$this->getName().' is Required');
				$errors[] = $error;
				return $errors;
			}
		}
		
		//list?
		if($this->getIsList()){
			if(isset($data)){
				if(!is_array($data)){
					$error = new KernelObject();
					$error->setObjectName('List Required');
					$error->setObjectDescription('Field '.$this->getName().' Requires a List of Values');
					$errors[] = $error;
					return $errors;
				}
			}
		}
		
		//type?
		$definitions = $this->getDefinitionName();
		if(is_array($definitions)){
			foreach($definitions as $definition){
				if(!in_array($definition, KernelObject::$_ReservedDefinitions)){
					//create an instance of the object using the supplied data and then validate it
					$dataCfg = array(
						'Definition'=>$definition,
						'Data'=>$data
					);
					$object = new KernelObject($dataCfg);
					
					echo 'need to handle complex objects validation<br/>';
				}else{
					if(!$this->validateReservedDefinitionData($definitions, $data)){
						$error = new KernelObject();
						$error->setObjectName('Invalid Definition');
						$error->setObjectDescription('Field '.$this->getName().' requires an Object that uses the "'.$definitions.'" Definition');
						$errors[] = $error;
					}
				}
			}
		}else{
			if(!in_array($definitions, KernelObject::$_ReservedDefinitions)){
				//create an instance of the object using the supplied data and then validate it
				$dataCfg = array(
					'Definition'=>$definitions,
					'Data'=>$data
				);
				
				$object = new KernelObject($dataCfg);
				if(!$object->validate()){
					
				}
			}else{
				if(!$this->validateReservedDefinitionData($definitions, $data)){
					$error = new KernelObject();
					$error->setObjectName('Invalid Definition');
					$error->setObjectDescription('Field '.$this->getName().' requires an Object that uses the "'.$definitions.'" Definition');
					$errors[] = $error;
				}
			}
		}
	}
	
	public function validateReservedDefinitionData($definition, $data){
		switch($definition){
			case 'Kernel.Object.String':
				return is_scalar($data);
				break;
			case 'Kernel.Object.Number':
				return is_numeric($data);
				break;
			case 'Kernel.Object.Boolean':
				return is_bool($data);
				break;
			case 'Kernel.Object.Date':
				return true;
				break;
		}
		if(is_scalar($data)){
			
		}
	}
	
	public function getValueJSON(){
		$fieldDef = array();
		$fieldDef['Name'] = $this->_fieldName;
		$fieldDef['Definitions'] = $this->_fieldDefinitions;
		$fieldDef['Required'] = $this->_required;
		$fieldDef['List'] = $this->_allowList;
		$fieldDef['Label'] = $this->_label;
		$fieldDef['IsPrimitive'] = $this->_primitive;
		
		$json = json_encode($fieldDef);
	}
}
?>