<?php

// debug. Will break the viewer.
$debug = false;

// include everything in the main file so the sub-classes can access it.
include_once __DIR__.'/lib/errors/errorHandler.php';
include_once __DIR__.'/lib/errors/error.php';

include_once __DIR__.'/lib/users/userHandler.php';
include_once __DIR__.'/lib/users/user.php';

include_once __DIR__.'/lib/settings/settingsHandler.php';

include_once __DIR__.'/lib/uploadHandler.php';

include_once __DIR__.'/lib/fileHandler.php';

include_once __DIR__.'/lib/web/webCore.php';


$errorHandler = new errorHandler();
$userHandler = new userHandler();
$settingsHandler = new settingsHandler();
$uploadHandler = new uploadHandler();
$fileHandler = new fileHandler();
$webCore = new webCore();

session_start();
if(!isset ($_SESSION['loggedin'])){
    $_SESSION['loggedin'] = false;
}

if ($debug){
var_dump($_SESSION);
var_dump($_POST);
var_dump($_GET);
}

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
  
  // viewing file
  if (!empty($_GET['id'])){
    
    // valid file
    if ($fileHandler->isValidId($_GET['id'])){
      if(empty($_GET['action'])){
        $webCore->buildPreview();
      }else{
        $fileHandler->showFile();
      }
    }else{
       $errorHandler->throwError("action:nofile");
    }
    
  }  
}
// they're performing an action, such as logging in or adding a user. Send it to the web core.
else if ((!empty($_POST)) and (empty($_FILES))){
    
  $webCore->process();
    
}else {
  
  
  $webCore->buildPage();
  
}


?>