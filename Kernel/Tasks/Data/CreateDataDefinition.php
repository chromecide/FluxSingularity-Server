<?php
class KernelTasksDataCreateDataDefinition extends KernelTasksTask{
	public function __construct(){
		parent::__construct(false);
		
		$this->_ClassName = 'Kernel.Tasks.Data.CreateDefinition';
		$this->_ClassTitle='Create an Entity Definition';
		$this->_ClassDescription = 'Creates an Entity Definition Object Using the supplied parameters.';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.4.0';
		
		$this->inputs['ClassName'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Class Name', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		$this->inputs['Title'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Title', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		$this->inputs['Description'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Description', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>false, 'AllowList'=>false));
		$this->inputs['Author'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Author', 'Type'=>'Kernel.Data.Security.User', 'Required'=>true, 'AllowList'=>false));
		$this->inputs['Version'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Version', 'Type'=>'Kernel.Data.Primitive.String', 'Required'=>true, 'AllowList'=>false));
		
		$this->inputs['Fields'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput', array('Name'=>'Fields', 'Type'=>'Kernel.Data.Primitive.FieldDefinition', 'Required'=>false, 'AllowList'=>true));
		
		$this->outputs['Definition'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'Definition', 'Type'=>'Kernel.Data.Entity.Definition'));
		$this->outputs['DefinitionCreated'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'DefinitionCreated', 'Type'=>'Kernel.Data.Primitive.Boolean'));
		$this->outputs['DefinitionNotCreated'] = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput', array('Name'=>'DefinitionNotCreated', 'Type'=>'Kernel.Data.Primitive.Boolean'));
	}
	
	public function runTask(){
		if(!parent::runTask()){
			return false;
		}
		
		$namespace = $this->getTaskInput('Namespace');
		$title = $this->getTaskInput('Title');
		$description = $this->getTaskInput('Description');
		$author = $this->getTaskInput('Author');
		$version = $this->getTaskInput('Version');
		
		$fields = $this->getTaskInput('Fields');
		
		$definition = DataClassLoader::createInstance('Kernel.Data.Entity.Definition');
		$definition->setValue('Namespace', $namespace);
		$definition->setValue('Title', $title);
		$definition->setValue('Description', $description);
		$definition->setValue('Author', $author);
		$definition->setValue('Version', $version);
		
		$definition->setValue('Fields', $fields);
		
		$this->setTaskOutput('Definition', $definition);
		$this->setTaskOutput('DefinitionCreated', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
		$this->setTaskOutput('DefinitionNotCreated', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', false));
		$this->setTaskOutput('Succeeded', DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean', true));
		
		return $this->completeTask();
	}
	
	public function validateInputs(){
		parent::validateInupts();
	}
}
?>