<?php
class DataClassLoader{
	private static $paths = array();
	
	public static function addPath($name, $path){
		if (!isset(self::$paths)) {
            self::$paths = array();
        }
        self::$paths[$name] = $path;
	}
	
	public static function getMeta(){
		$meta = $meta = new stdClass();
		$meta->Name = 'DataClassLoader';
		$meta->Title = 'Core Data Class Loader';
		$meta->Description = 'Loads Flux Singularity Classes based on dot notation';
		$meta->Author = 'Justin Pradier';
		$meta->Version = '0.9.0';
		
		return $meta;
	}
	
	public static function loadClass($className, $mongoObj=null, $dbConnector=null){
		if(!$objName = self::loadFromFile($className)){
			if($dbConnector){
				if(!$objName = self::loadFromDB($dbConnector, $mongoObj)){
					return false;
				}
			}else{
				return false;
			}
		}
		return $objName;
	}
	
	public static function loadFromFile($classPath){
		
		if($classPath==''){
			return false;
		}
		$classNameParts = explode('.', $classPath);
		
		if(!is_array($classNameParts) && count($classNameParts)>0){
			return false;
		}
		
		if(array_key_exists($classNameParts[0], self::$paths)){
			$basePath = self::$paths[$classNameParts[0]];
		}else{
			return false;
		}
		
		$currentPath = $basePath;
		$currentName = '';
		foreach($classNameParts as $part){
			$currentName.=$part;
			$currentPath .= '/'.$part;
			if(!class_exists($currentName)){
				$rootfilefound = false;
				if(!$rootfilefound && file_exists($currentPath.'.php')){
					$rootfilefound = true;
					
					if(!include_once $currentPath.'.php'){
						throw new Exception("Could not Include Class: $currentName", 1);
					}
				}
				
				if(!$rootfilefound && file_exists($currentPath.'/'.$part.'.php')){
					include_once $currentPath.'/'.$part.'.php';
				}else{
					if(!$rootfilefound){
						//throw new Exception("File Not Found: $currentName", 1);	
					}
				}
			}
		}
		
		return str_replace('.', '', $classPath);
	}
	
	public function loadFromDB($dbConnector, $mongoObj){
		return false;
	}
	
	public static function createInstance($className, $initCfg=null){
		$className = self::loadClass($className);
		
		if(class_exists($className)){
			if(method_exists($className, 'factory')){
				$return = null;
				$evalString = '$return = '.$className.'::factory($initCfg);';
				eval($evalString);
				return $return;
			}
			
			if(method_exists($className, 'singleton')){
				$evalString = 'return '.$className.'::singleton();';
				eval($evalString);
			}
			
			if($className == 'KernelDataPrimitiveList'){
				$item = new KernelDataPrimitiveList();
			}else{
				$item = new $className($initCfg);
			}
			
			return $item;
		}else{
			throw new Exception("Could not create class: $className", 1);
			//return 'could not find class';
		}
	}
	
	
}
?>