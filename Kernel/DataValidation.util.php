<?php 
class DataValidation{
	public static function getMeta(){
		$meta = $meta = new stdClass();
		$meta->Name = 'DataValidation';
		$meta->Title = 'Core Data Validation Routines';
		$meta->Description = 'Data Validation Routines for Flux Singularity Primitives';
		$meta->Author = 'Justin Pradier';
		$meta->Version = '0.1.0';
		
		return $meta;
	}
	
	public static function doString($value, $options){
		if(!$value || !$options || !is_array($options)){
			return false;
		}else{
			
		}
		return true;
	}
	
	public static function doNumber($value, $options=array()){
		return true;
	}
	
	public static function doBoolean($value, $options=array()){
		return true;
	}
	
	public static function doDate($value, $options=array(), $type='julian'){
		return true;
	}
	
	public static function doJulianDate($value, $options=array()){
		return true;
	}
	
	public static function doEmail($value, $options=array()){
		return true;
	}
	
	public static function doTimestamp($value, $options=array()){
		return true;
	}
	
	public function validateOptions($options){
		$numOptions = count($options);
		if($numOptions!=5){
			
		}else{
			
		}
	}
}
?>