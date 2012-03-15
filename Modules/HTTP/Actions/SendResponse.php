<?php
class ModulesHTTPActionsSendResponse extends KernelActionsAction{
	
	public function __construct($config=null){
		$this->addField('Response', 'Modules.HTTP.Object.Response', true);
	}
	
	public function run(){
		$responseObject = $this->getValue('ResponseObject');
		$httpContent = $responseObject->getValue('Content');
		
		echo $httpContent;
	}
}
?>