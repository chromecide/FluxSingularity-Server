<?php
class KernelDataDataStore extends KernelObject{
	protected $driver = null;
	
	public function __construct($config){
		
		$this->_ClassName = 'Kernel.Data.DataStore';
		$this->_ClassTitle='Kernel Base Data Store Object';
		$this->_ClassDescription = 'This is the base Data store object that all data sources are based on.';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.8.0';
		
		if(array_key_exists('Driver', $config)){
			if($this->loadDriver($config['Driver'])){
				$this->driver->connect($config);
			}
		}
	}
	
	protected function loadDriver($driverName, $config=null){
		$driverClass = 'Kernel.Data.Drivers.'.$driverName;
		$driver = DataClassLoader::createInstance($driverClass, $config);
		$this->driver = $driver;
		return true;
	}
	
	public function loadById($entity, $params){
		$collectionName = $entity->collectionName;
		$return = $this->driver->loadById($collectionName, $params);
		return $return;
	}
	
	public function find($entity, $params){
		$collectionName = $entity->collectionName;
		$return = $this->driver->find($collectionName, $params);
		return $return;
	}

	public function findOne($entity, $params){
		//print_r($entity);
		$collectionName = $entity->collectionName;
		$return = $this->driver->findOne($collectionName, $params);
		return $return;
	}
	
	public function save($entity){
		$collectionName = $entity->collectionName;
		$return = $this->driver->save($collectionName, $entity);
		return $return;
	}
	
	public function remove($entity){
		$collectionName = $entity->collectionName;
		$return = $this->driver->findOne($collectionName, $params);
		return $return;
	}
	
	public function getEntityReference($entity){
		$collectionName = $entity->collectionName;
		$return = $this->driver->getEntityReference($collectionName, $entity->getValue('KernelID'));
		return $return;
	}
}


?>