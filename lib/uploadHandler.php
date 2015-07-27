<?php

/*

@author: Pips

@title: Uploader Handler
@desc: Class that manages uploading from ShareX.

*/

class uploadHandler{
    
    protected $userHandler;
    protected $errorHandler;
    protected $settingsHandler;
    protected $fileHandler;
    
    function __construct(){
        
        $this->errorHandler = new errorHandler();
        $this->userHandler = new userHandler();
        $this->settingsHandler = new settingsHandler();
        $this->fileHandler = new fileHandler();
        
    }
    
  //bulk is done here
    function process(){
        
      $upload_okay = true;
      
      $uploader; // user who uploaded the file
      
      if(!isset($_POST['key'])){
        
        $this->errorHandler->throwError('upload:nokey');
        $upload_okay = false;
        
      }
      
      if($this->userHandler->isValidKey($_POST['key'])){
        
          $uploader = $this->userHandler->getUserByKey($_POST['key']);
        
        }else{
        
        $this->errorHandler->throwError('upload:wrongkey');
        $upload_okay = false;
        
        }
      
      if(!$uploader->enabled){
        
        $this->errorHandler->throwError('upload:banned');
        $upload_okay = false;
        
      }
      
      if(in_array(pathinfo($_FILES['file']['tmp_name'] . $_FILES['file']['name'], PATHINFO_EXTENSION), $this->settingsHandler->getSettings()['security']['disallowed_files'])){
      
      $this->errorHandler->throwError('upload:badextension');
      $upload_okay = false;
        
    }
      
      if($upload_okay){
        
        $this->fileHandler->saveFile($_FILES['file'], $uploader);
        
      }else{
         echo("ERROR!!!!!!!!!!!!!");
      }
     
        
    }
    
    // true/fase function to make sure that somebody is uploading, and with the right 'settings'
    function checkForUpload(){
        
        if(isset($_FILES['file'])){
            return true;
        }else{
            return false;
        }
        
    }

}

?>