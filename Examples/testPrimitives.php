<?php
require_once('../Kernel/DataClassLoader.php');
require_once('../Kernel/DataNormalisation.util.php');
require_once('../Kernel/DataValidation.util.php');

//Set the Paths to the Kernel and Modules folders
DataClassLoader::addPath('Kernel', realpath('../'));
DataClassLoader::addPath('Modules', realpath('../'));

//create a Kernel Instance
$FSKernel = DataClassLoader::createInstance('Kernel');

//Boolean
echo 'Testing Boolean Primitive<br/>';
	$boolean = DataClassLoader::createInstance('Kernel.Data.Primitive.Boolean');
	if($boolean){
		echo '&nbsp;&nbsp;&nbsp;&nbsp;Primitive Created<br/>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Default Value: ';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.($boolean->getValue()==true?'True':'False').'<br/>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Setting Value: ';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.($boolean->setValue(true)?'Passed':'Failed').'<br/>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Retrieving Value: ';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.($boolean->getValue()!==null?'Passed':'Failed').'<br/>';
	}else{
		echo '&nbsp;&nbsp;&nbsp;&nbsp;Error Creating Primitive<br/>';
	}
echo '<br/>';
//String
echo 'Testing String Primitive<br/>';
	$string = DataClassLoader::createInstance('Kernel.Data.Primitive.String');
	if($string){
		echo '&nbsp;&nbsp;&nbsp;&nbsp;Primitive Created<br/>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Default Value: ';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.($string->getValue()==''?'An Empty String':$string->getValue()).'<br/>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Setting Value: ';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.($string->setValue('Test')?'Passed':'Failed').'<br/>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Retrieving Value: ';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.(($string->getValue() == 'Test')?'Passed':'Failed').'<br/>';
	}else{
		echo '&nbsp;&nbsp;&nbsp;&nbsp;Error Creating Primitive<br/>';
	}echo '<br/>';
//Number
echo 'Testing Number Primitive<br/>';
	$number = DataClassLoader::createInstance('Kernel.Data.Primitive.Number');
	if($string){
		echo '&nbsp;&nbsp;&nbsp;&nbsp;Primitive Created<br/>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Default Value: ';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.(($number->getValue()!==null)?$number->getValue():'Null Value').'<br/>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Setting Value: ';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.($number->setValue(5)?'Passed':'Failed').'<br/>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Retrieving Value: ';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.(($number->getValue() == 5)?'Passed':'Failed').'<br/>';
	}else{
		echo '&nbsp;&nbsp;&nbsp;&nbsp;Error Creating Primitive<br/>';
	}

//DateTime

//Error

//FieldDefinition

//List

//Named List



//TaskInput

//TaskOutput

//Condition

//Condition Group
?>