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
	}
echo '<br/>';
//Number
echo 'Testing Number Primitive<br/>';
	$number = DataClassLoader::createInstance('Kernel.Data.Primitive.Number');
	if($number){
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
echo '<br/>';
//DateTime
echo 'Testing DateTime Primitive<br/>';
	$dateTime = DataClassLoader::createInstance('Kernel.Data.Primitive.DateTime');
	if($dateTime){
		echo '&nbsp;&nbsp;&nbsp;&nbsp;Primitive Created<br/>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Default Value: ';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.(($dateTime->getValue()!==null)?$dateTime->getValue():'Null Value').'<br/>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Setting Base Value: ';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.($dateTime->setValue('1979-07-28')?'Passed':'Failed').'<br/>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Retrieving Base Value: ';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.(($dateTime->getValue() !== null)?$dateTime->getValue():'Failed').'<br/>';
		
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Retrieving Year Value: ';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.(($dateTime->getValue('year') !== null)?$dateTime->getValue('year'):'Failed').'<br/>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Retrieving Month Value: ';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.(($dateTime->getValue('month') !== null)?$dateTime->getValue('month'):'Failed').'<br/>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Retrieving Month NameValue: ';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.(($dateTime->getValue('month') !== null)?$dateTime->getValue('monthname'):'Failed').'<br/>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Retrieving Day Value: ';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.(($dateTime->getValue('day') !== null)?$dateTime->getValue('day'):'Failed').'<br/>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Retrieving Hour Value: ';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.(($dateTime->getValue('hour') !== null)?$dateTime->getValue('hour'):'Failed').'<br/>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Retrieving Minute Value: ';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.(($dateTime->getValue('minute') !== null)?$dateTime->getValue('minute'):'Failed').'<br/>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Retrieving Second Value: ';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.(($dateTime->getValue('second') !== null)?$dateTime->getValue('second'):'Failed').'<br/>';
		
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Setting Year Value: ';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.($dateTime->setValue('Year', 1980)?'Passed':'Failed').'<br/>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Retrieving Base Value: ';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.(($dateTime->getValue() !== null)?$dateTime->getValue():'Failed').'<br/>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Setting Month Value: ';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.($dateTime->setValue('Month', 11)?'Passed':'Failed').'<br/>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Retrieving Base Value: ';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.(($dateTime->getValue() !== null)?$dateTime->getValue():'Failed').'<br/>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Setting Day Value: ';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.($dateTime->setValue('day', 30)?'Passed':'Failed').'<br/>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Retrieving Base Value: ';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.(($dateTime->getValue() !== null)?$dateTime->getValue():'Failed').'<br/>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Setting Hour Value: ';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.($dateTime->setValue('hour', 23)?'Passed':'Failed').'<br/>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Retrieving Base Value: ';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.(($dateTime->getValue() !== null)?$dateTime->getValue():'Failed').'<br/>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Setting Minute Value: ';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.($dateTime->setValue('minute', 23)?'Passed':'Failed').'<br/>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Retrieving Base Value: ';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.(($dateTime->getValue() !== null)?$dateTime->getValue():'Failed').'<br/>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Setting Second Value: ';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.($dateTime->setValue('second', 23)?'Passed':'Failed').'<br/>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Retrieving Base Value: ';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.(($dateTime->getValue() !== null)?$dateTime->getValue():'Failed').'<br/>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Retrieving Base Value(Alternate Format): ';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.(($dateTime->getValue() !== null)?$dateTime->getValue('l jS \of F, Y h:i a'):'Failed').'<br/>';
	}else{
		echo '&nbsp;&nbsp;&nbsp;&nbsp;Error Creating Primitive<br/>';
	}
echo '<br/>';

//List
echo 'Testing List Primitive<br/>';
	$list = DataClassLoader::createInstance('Kernel.Data.Primitive.List');
	
	if($list){
		echo '&nbsp;&nbsp;&nbsp;&nbsp;Primitive Created<br/>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Default Value: ';
		echo $list->getValue().'<br/>';
	}else{
		echo '&nbsp;&nbsp;&nbsp;&nbsp;Error Creating Primitive<br/>';
	}

echo '<br/>';

//Named List
echo 'Testing NamedList Primitive<br/>';
	$namedList = DataClassLoader::createInstance('Kernel.Data.Primitive.NamedList');
	
	if($namedList){
		echo '&nbsp;&nbsp;&nbsp;&nbsp;Primitive Created<br/>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Default Value: ';
		print_r($namedList->getValue());
		echo '<br/>';
	}else{
		echo '&nbsp;&nbsp;&nbsp;&nbsp;Error Creating Primitive<br/>';
	}

echo '<br/>';

//Error
echo 'Testing Error Primitive<br/>';
	$error = DataClassLoader::createInstance('Kernel.Data.Primitive.Error');
	
	if($error){
		echo '&nbsp;&nbsp;&nbsp;&nbsp;Primitive Created<br/>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Default Value: ';
		print_r($error->getValue());
		echo '<br/>';
	}else{
		echo '&nbsp;&nbsp;&nbsp;&nbsp;Error Creating Primitive<br/>';
	}

echo '<br/>';

//FieldDefinition
echo 'Testing FieldDefinition Primitive<br/>';
	$fieldDef = DataClassLoader::createInstance('Kernel.Data.Primitive.NamedList');
	
	if($fieldDef){
		echo '&nbsp;&nbsp;&nbsp;&nbsp;Primitive Created<br/>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Default Value: ';
		print_r($fieldDef->getValue());
		echo '<br/>';
	}else{
		echo '&nbsp;&nbsp;&nbsp;&nbsp;Error Creating Primitive<br/>';
	}

echo '<br/>';

//TaskInput
echo 'Testing TaskInput Primitive<br/>';
	$taskInput = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskInput');
	
	if($taskInput){
		echo '&nbsp;&nbsp;&nbsp;&nbsp;Primitive Created<br/>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Default Value: ';
		print_r($taskInput->getValue());
		echo '<br/>';
	}else{
		echo '&nbsp;&nbsp;&nbsp;&nbsp;Error Creating Primitive<br/>';
	}

echo '<br/>';

//TaskOutput
echo 'Testing TaskOutput Primitive<br/>';
	$taskOutput = DataClassLoader::createInstance('Kernel.Data.Primitive.TaskOutput');
	
	if($fieldDef){
		echo '&nbsp;&nbsp;&nbsp;&nbsp;Primitive Created<br/>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Default Value: ';
		print_r($taskOutput->getValue());
		echo '<br/>';
	}else{
		echo '&nbsp;&nbsp;&nbsp;&nbsp;Error Creating Primitive<br/>';
	}

echo '<br/>';

//Condition
echo 'Testing Condition Primitive<br/>';
	$condition = DataClassLoader::createInstance('Kernel.Data.Primitive.Condition');
	
	if($fieldDef){
		echo '&nbsp;&nbsp;&nbsp;&nbsp;Primitive Created<br/>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Default Value: ';
		print_r($condition->getValue());
		echo '<br/>';
	}else{
		echo '&nbsp;&nbsp;&nbsp;&nbsp;Error Creating Primitive<br/>';
	}

echo '<br/>';

//Condition Group
echo 'Testing ConditionGroup Primitive<br/>';
	$conditionGroup = DataClassLoader::createInstance('Kernel.Data.Primitive.ConditionGroup');
	
	if($fieldDef){
		echo '&nbsp;&nbsp;&nbsp;&nbsp;Primitive Created<br/>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Default Value: ';
		print_r($conditionGroup->getValue());
		echo '<br/>';
	}else{
		echo '&nbsp;&nbsp;&nbsp;&nbsp;Error Creating Primitive<br/>';
	}

echo '<br/>';
?>