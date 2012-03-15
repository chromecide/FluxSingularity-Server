<?php
require_once('Object.php');
require_once('ObjectDefinitionField.php');
require_once('DataDriver.php');
require_once('Action.php');
date_default_timezone_set('UTC');

class Kernel{
	private static $instance = null;
	
	protected $kernelPath = '../';
	protected $driver = null;
	
	public $numObjects = 0;
	
	public function __construct($config=null){
		if($config){
			if(array_key_exists('KernelPath', $config)){
				$this->kernelPath = $config['KernelPath'];
			}
			
			if(array_key_exists('KernelUserName', $config)){
				$this->kernelUserName = $config['KernelUserName'];
			}
			
			if(array_key_exists('KernelPassword', $config)){
				$this->kernelPassword = $config['KernelPassword'];
			}
		}
	}
	
	public static function singleton($cfg=null){
		if(!isset(self::$instance)){
			self::$instance = new Kernel($cfg);
		}
		
		return self::$instance;
	}
	
	public function setDataDriver($config){
		$driver = KernelDataDriver::load($config);
		$driver->connect();
		
		if($driver && $driver->isConnected()){
			$this->driver = $driver;
			KernelObject::$_DataDriver = $driver;
			return true;
		}else{
			return false;	
		}
	}
	
	public static function createObject($config){
		//$numObjects++;
		$returnObject = new KernelObject($config);
		
		return $returnObject;	
	}
	
	public function runAction($actionName, &$object){
		
		$filePath = $this->kernelPath.'/'.str_replace('.', '/', $actionName).'.php';
		
		include_once($filePath);
		
		$className = str_replace('.', '', $actionName);
		
		if(class_exists($className)){
			$action = new $className();
			
			$executed =  $action->run($object);
			
			return $executed;
		}else{
			return false;
		}
	}
	
	public function loadAction($actionName){
		
		$filePath = $this->kernelPath.'/'.str_replace('.', '/', $actionName).'.php';
		
		include_once($filePath);
		
		$className = str_replace('.', '', $actionName);
		
		if(class_exists($className)){
			$action = new $className();
			
			return $action;
		}else{
			return false;
		}
	}
	
	public static function createDefinition($name, $description, $fields){
		$returnObj = new KernelObjectDefinition();
	}
	
	public function getDefinitionList($object){
		if(!$object->getValue('_QueryType')){
			$object->setValue('_QueryType', 'Definition');
		}
		
		return $object->find();
	}
	
	public static function runTask(){
		
	}
	
	public static function runProcess(){
		
	}
	
	public function requireModule($moduleName, $autoInstall=false){
		$installed = false;
		$path = $this->kernelPath.'/'.str_replace('.', '/', $moduleName);
		if(is_dir($path)){
			//see if we can load the module information from the db
			$query = new KernelObject();
			$query->setValue('_QueryType','Modules');
			$query->setValue('Name', $moduleName);
			
			if($query->findOne()){
				$installed = true;
			}else{
				
				if($autoInstall){
					//attempt an install
					include_once($path.'/install.php');
					$moduleInstallerClass = str_replace('.', '', $moduleName).'Install';
					$installer = new $moduleInstallerClass();
					
					$installed = $installer->installModule();
				}else{
					$installed = false;
				}
				
			}
		}
		
		return $installed;
	}
	
	public function installCoreObjects(){
		//Kernel Definitions
		/*
		 * 'Kernel.Object',
		'Kernel.Definition',
		'Kernel.Process',
		'Kernel.Event',
		'Kernel.Action',
		'Kernel.Object.String',
		'Kernel.Object.Number',
		'Kernel.Object.Boolean',
		'Kernel.Object.Date',
		'Kernel.Module',
		'Kernel.Object.User',
		'Kernel.Object.Network',
		'Kernel.Object.Permission'
		 */
			//Kernel.Object
			$object = new KernelObject();
			$object->setObjectId('4f3f2f5a109cd838589db8fd');
			$object->addDefinition('Kernel.Definition');
			
			$object->setObjectName('Kernel.Object');
			$object->setObjectDescription('This is the base object that all other objects within Flux Singularity are based on');
			$object->save();
			
			//Kernel.Definition
			/*
			$object = new KernelObject();
			$object->setObjectId('4f3f2fe3109cd838589db8fe');
			$object->addDefinition('Kernel.Definition');
			$object->save();
			*/
			//Kernel.Event
			$object = new KernelObject();
			$object->setObjectId('4f3f2ff0109cd838589db8ff');
			$object->addDefinition('Kernel.Definition');
			$object->setObjectName('Kernel.Event');
			$object->setObjectDescription('Object Event Definition');
			$object->setObjectVersion('1.0.0');
			$object->setObjectAuthor('Justin Pradier');
			
			$object->addField('Event', 'Kernel.Object.String', true, false);
			$object->addField('TargetDefinition', 'Kernel.Object.String', true, false);
			$object->addField('Conditions', 'Kernel.Object', false, true);
			$object->addField('Actions', 'Kernel.Object.String', true, true, false);
			
			if(!$object->save()){
				print_r($object);
			}
			
			//Kernel.Action
			$object = new KernelObject();
			$object->setObjectId('4f3f2ff8109cd838589db900');
			$object->addDefinition('Kernel.Definition');
			$object->setObjectName('Kernel.Action');
			$object->setObjectDescription('Action Definition');
			$object->setObjectVersion('1.0.0');
			$object->setObjectAuthor('Justin Pradier');
			$object->addField('Status', 'Kernel.Object.String', true, false);
			$object->addEvent('BeforeRun', 'Kernel.Action');
			$object->addEvent('AfterRun', 'Kernel.Action');
			
			if(!$object->save()){
				print_r($object);
			}
			
			//Kernel.Query
			$object = new KernelObject();
			$object->setObjectId('4f3f2ff8109cd838589db907');
			$object->addDefinition('Kernel.Definition');
			$object->setObjectName('Kernel.Query');
			$object->setObjectDescription('Query Definition');
			$object->setObjectVersion('1.0.0');
			$object->setObjectAuthor('Justin Pradier');
			$object->addField('Type', 'Kernel.Object.String');
			$object->setValue('Type', 'AND');
			$object->addField('Conditions', 'Kernel.Condition', false, true, false);
			$object->addField('Results', 'Kernel.Object', false, true, false);
			$object->save();
			
			//Kernel.Condition
			$object = new KernelObject();
			$object->setObjectId('4f3f2ff8109cd838589db908');
			$object->addDefinition('Kernel.Definition');
			$object->setObjectName('Kernel.Condition');
			$object->setObjectDescription('Condition Definition');
			$object->setObjectVersion('1.0.0');
			$object->setObjectAuthor('Justin Pradier');
			$object->addField('FieldName', 'Kernel.Object.String');
			$object->addField('Operator', 'Kernel.Object.String');
			$object->addField('Value', 'Kernel.Object', false, true);
			$object->save();
			
			//Kernel.Module
			$object = new KernelObject();
			$object->setObjectId('4f3f3008109cd838589db901');
			$object->addDefinition('Kernel.Definition');
			$object->setObjectName('Kernel.Module');
			$object->setObjectDescription('System Module Definition');
			$object->setObjectVersion('1.0.0');
			$object->setObjectAuthor('Justin Pradier');
			$object->addField('ActionList', 'Kernel.Object.String', false, true);
			$object->addField('DefinitionList', 'Kernel.Object.String', false, true);
			$object->save();
			
			//Kernel.Object.User
			$object = new KernelObject();
			$object->setObjectId('4f3f3008109cd838589db902');
			$object->addDefinition('Kernel.Definition');
			$object->setObjectName('Kernel.Object.User');
			$object->setObjectName('Kernel.Object.User');
			$object->setObjectDescription('Security User Object');
			$object->setObjectVersion('1.0.0');
			$object->setObjectAuthor('Justin Pradier');
			$object->addField('Username', 'Kernel.Object.String', true, false);
			$object->addField('Password', 'Kernel.Object.String', true, false);
			$object->addField('DisplayName', 'Kernel.Object.String', true, false, true, 'Display Name');
			$object->addField('Networks', 'Kernel.Object.Network', false, true, false);
			$object->addEvent('LoggedIn', 'Kernel.Object.User');
			$object->addEvent('LoggedOut', 'Kernel.Object.User');
			$object->save();
			
			//Kernel.Object.Network
			$object = new KernelObject();
			$object->setObjectId('4f3f3008109cd838589db903');
			$object->addDefinition('Kernel.Definition');
			$object->setObjectName('Kernel.Object.Network');
			$object->setObjectDescription('Basic User Network used for Security');
			$object->setObjectVersion('1.0.0');
			$object->setObjectAuthor('Justin Pradier');
			$object->addField('Users', 'Kernel.Object.User', false, true, false);
			$object->addField('Networks', 'Kernel.Object.Network', false, true, false);
			
			$object->save();
			
		//Kernel Actions
		
			$object = $this->loadAction('Kernel.Actions.CreateObject');
			$object->setObjectId('4f3f3008109cd838589db904');
			$object->addDefinition('Kernel.Definition');
			if(!$object->save()){
				print_r($object->getObjectErrors());
			}
			
			$object = $this->loadAction('Kernel.Actions.Stop');
			$object->setObjectId('4f3f3008109cd838589db905');
			$object->addDefinition('Kernel.Definition');
			$object->save();
			
			$object = $this->loadAction('Kernel.Actions.FireEvent');
			$object->setObjectId('4f3f3008109cd838589db906');
			$object->addDefinition('Kernel.Definition');
			$object->save();
			
					
		//Kernel Events
	}
}

?>