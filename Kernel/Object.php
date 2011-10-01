<?php
/**
 * 
 * Base System Object
 * @author Justin Pradier <justin.pradier@fluxsingularity.com>
 * 
 */
class KernelObject{
	protected $initialConfig;
	
	public function __construct($config){
		$this->_ClassName = 'Kernel.Object';
		$this->_ClassTitle='Kernel Base Object';
		$this->_ClassDescription = 'This is the base object that all other objects within Flux Singularity are based on';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com>';
		$this->_ClassVersion = '0.8.0';
		
		$this->initialConfig = $config;
	}
	
	/**
	 * 
	 * Return the System name of the class
	 */
	public function getClassName(){
		return $this->_ClassName;
	}
	
	/**
	 * 
	 * Return the Title of the Class
	 */
	public function getClassTitle(){
		return $this->_ClassTitle;
	}
	
	/**
	 * 
	 * Return the Description of the Class
	 */
	public function getClassDescription(){
		return $this->_ClassDescription;
	}
	
	/**
	 * 
	 * Return the Author of the Class
	 */
	public function getClassAuthor(){
		return $this->_ClassAuthor;
	}
	
	/**
	 * 
	 * Return the Class Version
	 */
	public function getClassVersion(){
		return $this->_ClassVersion;
	}
	
	/**
	 * 
	 * Return the class Meta Information
	 */
	public function getClassMeta(){
		
		$meta = new stdClass();
		$meta->Name = $this->_ClassName;
		$meta->Title = $this->_ClassTitle;
		$meta->Description = $this->_ClassDescription;
		$meta->Author = $this->_ClassAuthor;
		$meta->Version = $this->_ClassVersion;
		
		return $meta;
	}
}
?>