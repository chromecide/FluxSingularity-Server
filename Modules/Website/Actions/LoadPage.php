<?php
class ModulesWebsiteActionsLoadPage extends KernelObject{
	public function __construct($cfg=null){
		parent::__construct($cfg);
		
		$this->useDefinition('Object.Action');
		
		$this->setValue('ID','Modules.Website.Actions.LoadPage');
		$this->setValue('Name','Modules.Website.Actions.LoadPage');
		$this->setValue('Description', 'Loads a Website Page');
		$this->setValue('Author', 'Justin Pradier');
		$this->setValue('Version', '1.0.0');
		
		$this->addAttribute('DomainName', array('Object.String'), true, false);
		$this->addAttribute('PathName', array('Object.String'), true, false);
		$this->addAttribute('Domain', array('Modules.Website.Object.Domain'));
		$this->addAttribute('Site', array('Modules.Website.Object.Website'));
		$this->addAttribute('Page', array('Modules.Website.Object.Page'));
		
		$this->addEvent('PageLoaded');
		$this->addEvent('PageNotLoaded');
	}
	
	public function run(&$inputObject){
		if(!parent::beforeRun($inputObject)){
			return false;
		}
		
		$erorred = false;
		
		$domain = $this->getValue('DomainName');
		$path = $this->getValue('PathName');
		$domainObject = new KernelObject();
		$domainObject->useDefinition('Modules.Website.Object.Domain');
		$domainObject->setValue('Domain', $domain);
		if($domainObject->findOne()){
			$this->setValue('Domain', $domainObject);
			$siteObject = new KernelObject();
			$siteObject->useDefinition('Modules.Website.Object.Website');
			$siteObject->addValue('Domains', $domainObject);
			if($siteObject->findOne()){
				$this->setValue('Site', $siteObject);
				$page = new KernelObject();
				$page->useDefinition('Modules.Website.Object.Page');
				$page->setValue('Path', $path);
				if($page->findOne()){
					$this->setValue('Page', $page);	
					
					$this->fireEvent('PageLoaded');
				}else{
					fb('Page Not Found');
					$this->fireEvent('PageNotLoaded');
				}	
			}else{
				fb('No Site found for domain');
				$this->fireEvent('PageNotLoaded');
			}
		}else{
			fb('Domain Not Found');
			$this->fireEvent('PageNotLoaded');
		}

		return parent::afterRun($inputObject);
	}
}
?>