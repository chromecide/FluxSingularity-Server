<?php
class ModulesWebsiteActionsPageToHTML extends KernelObject{
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->useDefinition('Object.Action');
		
		$this->setValue('ID','Modules.Website.Actions.PageToHTML');
		$this->setValue('Name','Page To HTML');
		$this->setValue('Description', 'Converts a Page Object to HTML');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
		
		$this->addAttribute('Page', array('Modules.Website.Object.Page'), true, false);
		$this->addAttribute('PageHTML', array('Object'), true, false);
		
		$this->addEvent('PageConverted');
		$this->addEvent('PageNotConverted');
	}
	
	public function run(&$inputObject){
		if(!parent::beforeRun($inputObject)){
			return false;
		}
		
		$erorred = false;
		
		$page = $this->getValue('Page');
		if($page){
			
			$contentBlocks = $page->getValue('ContentBlocks');
			$html = '';
			foreach($contentBlocks as $contentBlock){
				$block = new KernelObject();
				$block->loadById($contentBlock['ID']);
				$html .= $block->getValue('Content');
			}
			$this->setValue('PageHTML', $html);
			$this->fireEvent('PageConverted');
		}else{
			$this->fireEvent('PageNotConverted');
		}
		
		return parent::afterRun($inputObject);
	}
}
?>