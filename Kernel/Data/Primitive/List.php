<?php 
class KernelDataPrimitiveList extends KernelDataPrimitive{
	protected $items = array();
	protected $listType = 'Kernel.Data.Primitive.String';
	
	public function __construct($data=null){
		parent::__construct($data);
		
		$this->_ClassName = 'Kernel.Data.Primitive.List';
		$this->_ClassTitle='List Primitive';
		$this->_ClassDescription = 'Used for creating lists of other values';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.8.0';
		
		$this->data = array();	
		if(is_array($data)){
			$this->data = $data;
		}else{
			$this->data[] = $data;
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
	
	public function addItem($item){
		$this->items[] = $item;
	}
	
	public function getItem($index){
		return $this->items[$index];
	}
	
	public function getValue($field=null){
		if($field=='count'){
			return $this->Count();
		}else{
			return parent::getValue($field);
		}
	}
	
	public function toBasicObject(){
		$basicObj = array();
		
		foreach($this->items as $item){
			if($item instanceof KernelDataPrimitiveNamedList){
				$basicObj[] = $item->toBasicObject();
			}elseif(in_array('KernelDataPrimitive', class_parents($item))){
				$basicObj[] = $item->getValue();
			}elseif(in_array('KernelDataEntity', class_parents($item))){
				$basicObj[] = $item->toBasicObject();
			}else{
			}
		}
		return $basicObj;
	}
	
	public function toJSON(){
		$jsonObj  = $this->toBasicObject();
		return json_encode($jsonObj);
	}
}
?>