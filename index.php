<?php

// include everything in the main file
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

if ( (!isset($_SESSION['loggedin'])) or (!isset($_SESSION)) or (empty($_SESSION)) ){
    session_start();
    $_SESSION['loggedin'] = false;
}

$_SESSION['loggedin'] = true;

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