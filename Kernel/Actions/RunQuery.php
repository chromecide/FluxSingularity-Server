<?php
class KernelActionsRunQuery extends KernelObject{
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->useDefinition('Object.Action');
		
		$this->setValue('ID','Kernel.Actions.RunQuery');
		$this->setValue('Name','Run Query');
		$this->setValue('Description', 'Runs a Data Source Query');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
		
		$this->addAttribute('Query', 'Object.Query', true);
		
	}
	
	public function run(&$inputObject){
		if(!parent::beforeRun($inputObject)){
			return false;
		}
		
		$query = null;
		$query = $this->getValue('Query');
		if(!$query){
			$this->addError('No Query Supplied');
		}else{
			$query = $inputObject->getValue('Query');
		
			$results = $query->find();
			
			if($results){
				$results = $query->getValue('Results');
				$inputObject->setValue('Query', $query);
			}	
		}
		
		
		return parent::afterRun($inputObject);
	}
}
?>