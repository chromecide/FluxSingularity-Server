<?php
class ModulesHTTPActionsSendResponse extends KernelActionsAction{
	public function __construct($config=null){
		$this->addField('Request', 'Modules.HTTP.Response');
	}
	
	public function run(){
		$requestObject  = $this->getValue('Request');
		
		$domain = $_SERVER['HTTP_HOST'];
		$path = $_SERVER['REQUEST_URI'];
		$postArray = $_POST;
		$getArray = $_GET;
		$referer = $_SERVER['HTTP_REFERER'];
		$remoteAddr = $_SERVER['REMOTE_ADDR'];
		$userAgent = $_SERVER['HTTP_USER_AGENT'];
		
		$requestObject->setValue('Domain', $domain);
		$requestObject->setValue('Path', $path);
		$requestObject->setValue('POST', $postArray);
		$requestObject->setValue('GET', $getArray);
		$requestObject->setValue('REFERER', $referer);
		$requestObject->setValue('REMOTE_ADDRESS', $remoteAddr);
		$requestObject->setValue('USER_AGENT', $userAgent);
		
		$this->setValue('Request', $requestObject);
	}
}
?>