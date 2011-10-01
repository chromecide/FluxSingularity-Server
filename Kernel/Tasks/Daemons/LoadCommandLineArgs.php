<?php
class KernelTasksDaemonsLoadCommandLineArgs extends KernelTasksTask{
	public function __construct(){
		
		$this->_ClassName = 'Kernel.Tasks.Daemons.LoadCommandLineArgs';
		$this->_ClassTitle='Load Command Line Arguments';
		$this->_ClassDescription = 'This task loads command line arguments when a process is executed from a command line';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.8.0';
		
		$this->outputs['ArgCount'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Argument Count', 'Type'=>'Kernel.Data.Primitive.Number'));
		$this->outputs['Arguments'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Arguments', 'Type'=>'Kernel.Data.Primitive.NamedList'));
		
		$this->outputs['ArgumentsLoaded'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Arguments Loaded', 'Type'=>'Kernel.Data.Primitive.Boolean'));
		$this->outputs['NoArgumentsLoaded'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'No Arguments Loaded', 'Type'=>'Kernel.Data.Primitive.Boolean'));
	}
	
	public function runTask(){
		global $argc, $argv;
		
		if(!parent::runTask()){
			return false;
		}
		
		$Args = DataClassLoader::createInstance('Kernel.Data.Primitive.NamedList');
		
		if($argc>0){
			foreach($argv as $key=>$value){
				$Args->addItem($key, DataClassLoader::createInstance('Kernel.Data.Primitive.String', $value));
			}

			$this->setTaskOutput('Arguments', DataClassLoader::createInstance('Kernel.Data.Primitive.Number', $Args));
			$this->setTaskOutput('ArgCount', $argc);
			
			$this->setTaskOutput('ArgumentsLoaded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
			$this->setTaskOutput('NoArgumentsLoaded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));
		}else{
			$this->setTaskOutput('ArgumentsLoaded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));
			$this->setTaskOutput('NoArgumentsLoaded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
		}
		
		return $this->completeTask();
	}
}
?>