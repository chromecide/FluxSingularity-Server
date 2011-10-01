<?
class ModulesInstallerProcessesRunInstaller extends KernelProcessesProcess{
	public function __construct(){
		
		parent::__construct();
	}
	
	public function runProcess(){
		
		
		$process = array(
			'LocalData'=>array(
				'FSNameString'=>DataClassLaoder::createInstance('Kernel.Data.Primitive.String', 'Flux Singularity')
			),
		);
		
		parent::runProcess();
	}
}
?>