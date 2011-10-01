<?php 
class DataNormalization{
	public static function getMeta(){
		$meta = $meta = new stdClass();
		$meta->Name = 'DataNormalization';
		$meta->Title = 'Core Data Normalization Class';
		$meta->Description = 'Data Normalisation Functions for Flux Singularity Primitive Types';
		$meta->Author = 'Justin Pradier';
		$meta->Version = '0.1.0';
		
		return $meta;
	}
	
	public static function doString($value){
		return $value;
	}
	
	public static function doBoolean($value){
		if($value===true || $value === false){
			return $value;
		}else{
			$value = strtolower($value);
			if($value=='true' || $value =='1' || $value=='yes'){
				return true;
			}
			if($value=='false' || $value=='0' || $value=='no'){
				return false;
			}
			return false;
		}
	}
	
	public static function doDate($value, $type='julian', $inputformat='d/m/Y'){
		switch($type){
			case 'julian':
			default:
				return self::doJulianDate($value);
				break;
		}
	}
	
	public static function doJulianDate($value){
		return $value;
	}
	
	public static function doTimestamp($value){
		return $value;
	}
	
	public static function doComparisonOperator($value){
		if($value instanceof KernelDataPrimitiveString){
			$value = $value->get();
		}
		switch(strtolower($value)){
			// ">" operator
			case '>';
			case 'greater than':
			case 'is greater than':
				return '>';
				break;
			// "<" operaator
			case '<':
			case 'less than':
			case 'is less than':
				return '<';
				break;
			// ">=" operator
			case '>=':
			case 'greater than or equal to':
				return '>=';
				break;
			// "<=" operator
			case '<=':
			case 'less than or equal to':
				return '<=';
				break;
			// "!=" operator
			case '!=':
			case 'not equal to':
			case 'not equal':
			case '<>':
				return '!=';
				break;
			// "@" contains operator
			case '@':
			case 'contains':
				return '@';
				break;
			// "==" operator
			case '':
			case '=':
			case '==':
			case 'equals':
			case 'is equal to':
			default:
				return '==';
				break;
		}
	}
}
?>