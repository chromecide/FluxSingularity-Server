<?php 
class KernelDataPrimitiveConditionGroup extends KernelDataPrimitive{
	public function __construct($data){
		parent::__construct($data);
		
		$this->_ClassName = 'Kernel.Data.Primitive.Condition';
		$this->_ClassTitle='Search Condition Primitive';
		$this->_ClassDescription = 'Condition Objects are used for searching entity data objects';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.7.0';
		
		$this->fields['Type'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Type', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		$this->fields['Conditions'] = DataClassLoader::createInstance('Kernel.Data.Primitive.FieldDefinition', array('Name'=>'Conditions', 'Type'=>'Kernel.Data.Primitive.Condition', 'Required'=>true, 'AllowList'=>true));
		
		$this->loadData($data);
	}
	
	public function setValue($field, $value){
		$this->data[$field] = $value;
	}
	
	public function getValue($field){
		return $this->data[$field];
	}
	
	public function addCondition($attribute, $operator='==', $value=null){
		if($attribute instanceof KernelDataPrimitiveCondition || $attribute instanceof KernelDataPrimitiveConditionGroup){
			$this->data['Conditions'][] = $attribute;
		}else{
			$params = array(
				'Attribute'=>$attribute,
				'Operator'=>$operator,
				'Value'=>$value
			);
			
			$item = DataClassLoader::createInstance('Kernel.Data.Primitive.Condition', $params);
			
			$this->data['Conditions'][]=$item;
		}
	}
	
	public function getConditions(){
		$localConditions =  $this->data['Conditions'];
		$conditions = DataClassLoader::createInstance('Kernel.Data.Primitive.List');
		
		foreach($localConditions as $condition){
			$conditions->addItem($condition);
		}
		
		return $conditions;
	}
	
	/**
	 * array(
	 * 		'Type'=>'AND',
	 * 		'Conditions'=>array(
	 * 			array(
	 * 				'Attribute'=>'Username',
	 * 				'Operator'=>'==',
	 * 				'Value'=>'ExampleUser'
	 * 			),
	 * 			array(
	 * 				'Attribute'=>'Password',
	 * 				'Operator'=>'==',
	 * 				'Value'=>'Example Password'
	 * 			)
	 * 		)
	 * )
	 * array(
	 * 		'Type'=>'AND',
	 * 		'Conditions'=>array(
	 * 			array(
	 * 				'Type'=>'OR',
	 * 				'Conditions'=>array(
	 * 					array(
	 * 						'Attribute'=>'Username',
	 * 						'Operator'=>'==',
	 * 						'Value'=>'ExampleUser'
	 * 					),
	 * 					array(
	 * 						'Attribute'=>'Username',
	 * 						'Operator'=>'==',
	 * 						'Value'=>'ExampleUser2'
	 * 					)
	 * 				)
	 * 				
	 * 			),
	 * 			array(
	 * 				'Attribute'=>'Password',
	 * 				'Operator'=>'==',
	 * 				'Value'=>'Example Password'
	 * 			)
	 * 		)
	 * )
	 */
	public function loadData($data){
		//parent::loadData($data);
		if(is_array($data)){
			foreach($data as $key=>$value){
				switch($key){
					case 'Type':
						if($value instanceof KernelDataPrimitiveString){
							$this->setValue($key, $value);
						}else{
							$this->setValue($key, DataClassLoader::createInstance('Kernel.Data.Primitive.String', $value));
						}
						break;
					case 'Conditions':
						if(is_array($value)){
							foreach($value as $conditionItem){
								if(array_key_exists('Type', $conditionItem)){ //it's a condition group
									$newItem = DataClassLoader::createInstance('Kernel.Data.Primitive.ConditionGroup', $conditionItem);
								}else{//it's a condition
									$newItem = DataClassLoader::createInstance('Kernel.Data.Primitive.Condition', $conditionItem);
								}
								
								$this->addCondition($newItem);
							}	
						}
						break;
				}
			}
		}
	}	
}
?>