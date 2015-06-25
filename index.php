<?php

// include everything in the main file, so 
include_once __DIR__.'/lib/errorHandler.php';
include_once __DIR__.'/lib/userHandler.php';
include_once __DIR__.'/lib/settings.php';
include_once __DIR__.'/lib/uploadHandler.php';
include_once __DIR__.'/lib/fileHandler.php';

$errorHandler = new errorHandler();
$userHandler = new userHandler();
$settings = new settings();
$uploadHandler = new uploadHandler();
$fileHandler = new fileHandler();

// somebody is uploading, so we send it to the upload handler

if (!empty($_FILES)){
    
    if($uploadHandler->checkForUpload()){
        $uploadHandler->process();
        
    }else{
      $errorHandler->throwError("upload:wrongfile");
    }
    
}
// they're accessing a file, so we go to the file Handler.
else if (!empty($_GET)){

  // WHY WONT THIS WORK
  if($fileHandler->isValidId($_GET['id'])){
      $fileHandler->showFile($_GET['id']);
    
  }else{
    $errorHandler->throwError("action:nofile");
  }
  
}
// they're performing an action, such as logging in or adding a user. Send it to the web core.
else if ((!empty($_POST)) and (empty($_FILES))){
  
  
  
}




?>