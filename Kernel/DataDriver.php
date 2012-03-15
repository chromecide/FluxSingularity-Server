<?php

class KernelDataDriver {
	function __construct($config){
		
	}
    
	public static function load($driverCfg){
		$driverName = $driverCfg['Driver'];
		if(include_once('DataDrivers/'.$driverName.'.php')){
			$driverNameString = 'KernelDataDriver'.$driverName;
			
			return new $driverNameString($driverCfg);
		}else{
			throw new Exception("DataDriver $driverName Not Found", 1);
		}	
	}
	
	public function connect($config){
		throw new Exception('Database Driver Must Implement connect');
	}
	
	public function disconnect(){
		throw new Exception('Database Driver Must Implement disconnect');
	}
	
    public function loadById(){
    	throw new Exception('Database Driver Must Implement loadById');
    }
    
    public function find(){
    	throw new Exception('Database Driver Must Implement find');
    }
    
    public function findOne(){
    	throw new Exception('Database Driver Must Implement findOne');
    }
    
    public function save(){
    	throw new Exception('Database Driver Must Implement save');
    }
    
    public function remove(){
    	throw new Exception('Database Driver Must Implement remove');
    }
    
    public function getEntityReference(){
    	throw new Exception('Database Driver Must Implement getEntityReference');
    }
	
	public function isConnected(){
		throw new Exception('Database Driver Must Implement isConnected');
	}
}
?>
