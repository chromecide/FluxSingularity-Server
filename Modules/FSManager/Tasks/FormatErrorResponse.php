<?php 
class ModulesFSManagerTasksFormatErrorResponse extends KernelTasksTask{
	public function __construct(){
		parent::__construct();
		$this->kernelClass = 'Modules.FSManager.Tasks.FormatErrorResponse';
		$this->inputs['Message'] = array('Message', 'Modules.FSManager.Data.Message', true, false);
		$this->inputs['Errors'] = array('Errors', 'Kernel.Data.Primitive.String', true, true);
		
		$this->outputs['ErrorResponse'] = array('ErrorResponse', 'Modules.FSManager.Data.Error');
	}
	
	public function runTask(){
		$errors = $this->getTaskInput('Errors');
		$errorObj = DataClassLoader::createInstance('Modules.FSManager.Data.Error');
		
		if($errors instanceof KernelDataPrimitiveList){
			$count = $errors->get('count');
			
			$errorStr = '';
			for($i=0;$i<$count;$i++){
				$error = $errors->getItem($i);
				$errorStr .= $error->get().'<br/>';
			}
		}else{
			if($errors){
				$errorStr = $errors->get();
			}else{
				$errorStr = 'Unidentified Error';
			}
		}
		
		$message = $this->getTaskInput('Message');
		$message->set('Status', new KernelDataPrimitiveString('Error'));
		$message->set('Responses', new KernelDataPrimitiveString($errorStr));
		
		$this->setTaskOutput('Message', $message);
		$this->setTaskOutput('Completed', new KernelDataPrimitiveBoolean(true));
	}
}
?>