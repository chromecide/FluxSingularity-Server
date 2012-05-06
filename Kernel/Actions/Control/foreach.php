<?php
class KernelActionsDataForEach extends KernelObject{
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->useDefinition('Object.Action');
		
		$this->setValue('ID','Kernel.Actions.Data.ForEach');
		$this->setValue('Name','For Each');
		$this->setValue('Description', 'Runs an action for each item in a list');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
		
		$this->addAttribute('List', 'Object', true, true);
		$this->addAttribute('Action', 'Object.Action');
		$this->addAttribute('ListActionMap', 'Object.AttributeMap', false, true);
		
		$this->addEvent('NoItems');
	}
	
	public function run(&$inputObject){
		if(!parent::beforeRun($inputObject)){
			return false;
		}
		
		$list = $this->getValue('List');
		$action = $this->getValue('Action');
		$map = $this->getValue('ListActionMap');
		
		if(!is_array($list) || count($list)==0){
			$this->fireEvent('NoItems');
		}else{
			if(!$action instanceof KernelObject){
				
			}else{
				if(!$action->usesDefinition('Object.Action')){
					
				}else{
					foreach($list as $item){
						$action->run($item);
					}
				}
			}
		}
		return parent::afterRun($inputObject);
	}
}
?>