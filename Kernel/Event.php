<?php
class KernelEvent extends KernelObject{
	public static $_EventTypes = array(
		'BeforeCreate',
		'AfterCreate',
		'BeforeDataChange',
		'AfterDataChange',
		'BeforeAddField',
		'AfterAddField',
		'BeforeRemoveField',
		'AfterRemoveField',
		'BeforeSave',
		'AfterSave',
		'BeforeLoad',
		'AfterLoad',
		'BeforeRunAction',
		'AfterRunAction'
	);
	
	public static $_EventActions = array(
		'CreateObject',
		'SaveObject',
		'RemoveObject',
		'SetFieldValue',
		'AddObjectField',
		'RemoveObjectField'
	);
	
	public function __construct(){
		
	}
	
	public function fire(){
		
	}
}
?>