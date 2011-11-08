<?php 
class KernelDataPrimitiveConditionGroup extends KernelDataPrimitiveNamedList{
	public function __construct($data){
		parent::__construct($data);
		
		$this->_ClassName = 'Kernel.Data.Primitive.Condition';
		$this->_ClassTitle='Search Condition Primitive';
		$this->_ClassDescription = 'Condition Objects are used for searching entity data objects';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.7.0';
		
		$this->setValue('Type', DataClassLoader::createInstance('Kernel.Data.Primitive.String', 'Kernel.Data.Primitive.String'));
		$this->setValue('Conditions', DataClassLoader::createInstance('Kernel.Data.Primitive.List'));
		$this->loadData($data);
	}
	
	public function addCondition($attribute, $operator='==', $value=null){
		$conditions = $this->getValue('Conditions');
		if(!($conditions instanceof KernelDataPrimitiveList)){
			$conditions = DataClassLoader::createInstance('Kernel.Data.Primitive.List');
		}
		if($attribute instanceof KernelDataPrimitiveCondition || $attribute instanceof KernelDataPrimitiveConditionGroup){
			$conditions->addItem($attribute);
		}else{
			$params = array(
				'Attribute'=>$attribute,
				'Operator'=>$operator,
				'Value'=>$value
			);
			
			$item = DataClassLoader::createInstance('Kernel.Data.Primitive.Condition', $params);
			
			$conditions->addItem($item);
		}
		$this->setValue('Conditions', $conditions);
	}
	
	public function getConditions(){
		$conditions = $this->getValue('Conditions');
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