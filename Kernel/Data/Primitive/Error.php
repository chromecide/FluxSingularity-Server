<?php
class KernelDataPrimitiveError extends KernelDataPrimitiveNamedList{
	public function __construct($data){
		parent::__construct();
		
		$this->_ClassName = 'Kernel.Data.Primitive.Error';
		$this->_ClassTitle='Error Primitive Object';
		$this->_ClassDescription = 'Used to create Error reports';
		$this->_ClassAuthor = 'Justin Pradier <justin.pradier@fluxsingularity.com';
		$this->_ClassVersion = '0.4.0';
		
		$this->addItem('Class', null);
		$this->addItem('Message', null);
	}
}