<?php 
class ModulesFSManagerTasksOutputMessages extends KernelTasksTask{
	public function __construct(){
		$this->kernelClass = 'Modules.FSManager.Tasks.OutputMessages';
		$this->inputs['Messages'] = array('Messages', 'Modules.FSManager.Data.Message', true, true);
	}
	
	public function runTask(){
		$messages = $this->getTaskInput('Messages');
		
		if($messages instanceof KernelDataPrimitiveList){
			$messageCount = $messages->get('count');
			echo '[';
			for($i=0; $i<$messageCount;$i++){
				$message = $messages->getItem($i);
				if($i!=0){
					echo ',';
				}
				echo $message->toJSON();
			}
			echo ']';
		}else{
			echo $messages->toJSON();
		}
		$this->setTaskOutput('Completed', new KernelDataPrimitiveBoolean(true));
	}
}
?>