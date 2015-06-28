<?php

// include everything in the main file
include_once __DIR__.'/lib/errorHandler.php';
include_once __DIR__.'/lib/userHandler.php';
include_once __DIR__.'/lib/settings.php';
include_once __DIR__.'/lib/uploadHandler.php';
include_once __DIR__.'/lib/fileHandler.php';
include_once __DIR__.'/lib/webCore.php';
include_once __DIR__.'/lib/error.php';
include_once __DIR__.'/lib/user.php';

$errorHandler = new errorHandler();
$userHandler = new userHandler();
$settings = new settings();
$uploadHandler = new uploadHandler();
$fileHandler = new fileHandler();
$webCore = new webCore();

session_start(); // we always start the session

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

  if(empty($_GET['action']) ){
  
    if($fileHandler->isValidId($_GET['id'])){
        $webCore->buildPreview();

    }else{
      $errorHandler->throwError("action:nofile");
    }
  }else{
     if($fileHandler->isValidId($_GET['id'])){
        $fileHandler->showFile();

    }else{
      $errorHandler->throwError("action:nofile");
    }
  }
  
}
// they're performing an action, such as logging in or adding a user. Send it to the web core.
else if ((!empty($_POST)) and (empty($_FILES))){
  
  
}
// show admin panel
else if ( (isset($_SESSION['loggedin'])) and ($_SESSION['loggedin']) ){

  
}
// show login page
else if ( (!isset($_SESSION['loggedin'])) or (!$_SESSION['loggedin']) ) {
  
  
}




?>