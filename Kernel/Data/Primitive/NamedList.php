<?php 
class KernelDataPrimitiveNamedList extends KernelDataPrimitive{
	protected $items = array();
	protected $listType = 'Kernel.Data.Primitive.String';
	
	public function __construct($data){
		parent::__construct($data);
		
		$this->_ClassName = 'Kernel.Data.Primitive.NamedList';
		$this->_ClassTitle='Named List Primitive Object';
		$this->_ClassDescription = 'Used to create key/value pairs as an entity value';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.8.0';
		
		$this->data = array();
		if($data){	
			if(is_array($data) || is_object($data)){
				foreach($data as $field=>$value){
					if(is_array($value)){
						if(array_key_exists('KernelClass', $value)){
							$this->addItem($field, DataClassLoader::createInstance($value['KernelClass'], $value));
						}
					}else{
						$this->addItem($field, DataClassLoader::createInstance('Kernel.Data.Primitive.String', $value));
					}
				}
			}else{
				$this->data[] = $data;
			}
		}
	}
	
	public function setType($type){
		$this->listType = $type;
	}
	
	public function getType(){
		return $this->listType;
	}
	
	public function Count(){
		return count($this->items);
	}
	
	public function addItem($name, $value){
		$this->items[$name] = $value;
	}
	
	public function setValue($name, $value){
		$this->addItem($name, $value);
	}
	
	public function getValue($name){
		if(!$name){
			return $this->toBasicObject();
		}else{
			return $this->getItem($name);
		}
		
	}
	
	public function getItem($name){
		return $this->items[$name];
	}
	
	public function toBasicObject(){
		$ret = new stdClass();
		
		foreach($this->items as $key=>$value){
			echo $key.'<br/>';
			if($value instanceof KernelDataPrimitiveNamedList){
				$ret->$key = $value->toBasicObject();
			}elseif(in_array('KernelDataPrimitive', class_parents($value))){
				$ret->$key = $value->getValue();
			}elseif(in_array('KernelDataEntity', class_parents($value))){
				$ret->$key = $value->toBasicObject();
			}
		}
		
		return $ret;
	}
	
}
?>