<?php 
/*
 JSON Representation
 
 
 
 
 */

class ModulesFSManagerProcessesUI extends KernelProcessesProcess{
	public function __construct(){
		
		$this->inputEvents['PageRequest'] = array('Modules.Websites.Events.PageRequest', true);
		
		$this->buildTaskMap();
		
		parent::__construct();
	}
	
	public function buildTaskMap(){
		//build the show page task
		$html = DataClassLoader::createInstance('Kernel.Data.Primitive.String', '
			<html>
				<head>
					<title>Flux Singularity</title>
					<link rel="stylesheet" type="text/css" href="./vendors/ext4-0-0/resources/css/ext-all.css"></link>
					<link rel="stylesheet" type="text/css" href="./vendors/DockUI_0_2/DockUI.css"></link>
					
					<script type="text/javascript" src="./vendors/ext4-0-0/ext.js"></script>
				  	<script type="text/javascript" src="./vendors/ext4-0-0/ext-all-debug.js"></script>	
				  	
				  	<script type="text/javascript" src="./vendors/DockUI_0_2/Models.js"></script>
				  	
				  	<script type="text/javascript" src="./engine/client/kernel/Models/Viewport.js"></script>
				  	<script type="text/javascript" src="./engine/client/kernel/Models/Router.js"></script>
				  	
				  	<script type="text/javascript" src="./engine/client/kernel/Services/Router.js"></script>
				  	
				  	<script language="javascript">
				  		Ext.Loader.setConfig({
							enabled:true,
							enableCacheBuster:true
					  	});
				  		
			        	Ext.Loader.setPath("DockUI", "/vendors/DockUI_0_2");
			        	Ext.Loader.setPath("FSManager", "/engine/client/kernel");
			
			        	Ext.require("FSManager.System.Clients.Web");
			        	
			        	var CLIENT;
			        	var Router;
			        	
			        	Ext.onReady(function(){
			        		CLIENT = Ext.create("FSManager.System.Clients.Web",{
			        			
			        		});
			        		
			        		CLIENT.initSession();
						});
				  	</script>
				</head>
				<body>
					<script language="javascript">
						
						
					</script>
				</body>
			</html>
		');
		
		$this->setTokenData('OutputHTML', 'HTMLString', $html);
		
		//build the output HTML Task
		$outputHTMLTask = DataClassLoader::createInstance('Modules.Website.Tasks.SendHTMLResponse');
		$this->tasks['OutputHTML'] = $outputHTMLTask;
	}
}
?>