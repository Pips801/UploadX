<?php

include_once __DIR__.'/lib/errorHandler.php';
include_once __DIR__.'/lib/userHandler.php';
include_once __DIR__.'/lib/settings.php';
include_once __DIR__.'/lib/uploadHandler.php';

$errorHandler = new errorHandler();
$userHandler = new userHandler();
$settings = new settings();
$uploadHandler = new uploadHandler();

// somebody is uploading
if (!empty($_FILES)){
    
    if($uploadHandler->checkForUpload()){
        $uploadHandler->process();
        
    }
    
}
// they're accessing a file, so we go ot the file Handler.
else if (!empty($_GET)){
    
    
    
}

?>