# Flux Singularity Server

## Introduction
This project encompasses the PHP Server Framework for Flux Singularity.  

## Using Flux Singularity Server
  
The following is the minimum requirements for a PHP file to have access to the Flux Singularity Kernel  
  
>//Load the Data Loader and Utility Classes  
>require\_once('../Kernel/DataClassLoader.php');  
>require\_once('../Kernel/DataNormalisation.util.php');  
>require\_once('../Kernel/DataValidation.util.php');  
  
>//Add the Kernel Path to the Data Loader  
>DataClassLoader::addPath('Kernel', realpath('../'));  
>//If using Modules outside of the Core Kernel, add the Module path to the Data Loader
>DataClassLoader::addPath('Kernel', realpath('../'));  
>  
>//Create an Instance of the Flux Singularity Kernel  
>$FSKernel = DataClassLoader::createInstance('Kernel');  
  
## Examples
  
### Run a Task  
  
>//Create the Task and Input Variables  
>$taskName = 'Kernel.Data.Math.Add';  
>$taskInputs = array(  
>  'Input1'=>3,  
>  'Input2'=>5,  
>);
>    
>//run the task through the Kernel Instance, which will return a Task Object  
>$task = $FSKernel->runTask($taskName, $inputs);  
>  
>//print the value of the 'Result' Output  
>echo $task->getTaskOutput('Result')->getValue();  
  
### Run a Process  
  
### Fire an Event  
  
### Build and Run a Temporary Process  
  
### Saving and Loading Data  
