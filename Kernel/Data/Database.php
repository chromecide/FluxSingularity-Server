<?php
class KernelDataDatabase{
	public static $instance;
    public static function factory($name){
    	$driverClassName = DataClassLoader::loadClass($name);
    	if(!self::$instance){
    		$driverObj = false;
    		$evalString = '$driverObj = '.$driverClassName.'::singleton();';
    		eval($evalString);
    		self::$instance = $driverObj;
    	}
    	return self::$instance;
    }
	
	public static function getMeta(){
		$meta = $meta = new stdClass();
		$meta->Name = 'KernelDataDatabase';
		$meta->Title = 'Core Database Driver Factory';
		$meta->Description = 'Handles the creation of dynamic Data Sources and Stores';
		$meta->Author = 'Justin Pradier';
		$meta->Version = '0.8.0';
		
		return $meta;
	}
}
?>