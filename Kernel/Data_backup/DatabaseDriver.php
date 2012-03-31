<?php

class KernelDataDatabaseDriver extends KernelObject{
	public function __construct($config){
		parent::__construct($config);
		
		$this->_ClassName = 'Kernel.Data.DatabaseDriver';
		$this->_ClassTitle='Kernel Base Database Driver Object';
		$this->_ClassDescription = 'This is the base object that all other database drivers within Flux Singularity are based on';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.8.0';
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
}
?>
