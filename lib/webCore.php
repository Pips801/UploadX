<?php

/*

ACTIONS
 - createuser
  args - 'username'
  
 - deleteuser
  args - 'username'
  
 - edituser
  args - 'username', ''
 - login
 - logout
 - changepassword
 - deletefile

*/
  

class webCore{
  
  protected $settings;
  protected $userHandler;
  protected $errorHandler;
  protected $fileHandler;
  
  function __construct(){
    
    $this->errorHandler = new errorHandler();
    $this->settings = new settings();
    $this->userHandler = new userHandler();
    $this->fileHandler = new fileHandler();
    
    
  }
  
  function processRequest(){
    
    $action = $_POST['action'];
    $message;
    
    // create user
    if( ($action == 'createuser') ){
      
      if(!$this->userHandler->isUser($_POST['username'])){

        $this->userHandler->createUser($_POST['username']);

        $message = "Created user <b>".$_POST['username']."</b>";

        include __DIR__.'/templates/notification.php';
      }else{
        
        $this->errorHandler->throwError('action:badusername');
        
      }
      
      
    }
    
    // delete user
    if ($action == 'deleteuser'){
      
      if($this->userHandler->isUser($_POST['username'])){
        
        $this->userHandler->deleteUser($_POST['username']);
        
      }else{
        
        $this->errorHandler->throwError('action:nouser');
        
      }
      
    }
    
    if ($action == 'login'){
      
      
      
    }
    
  }
  
  function buildPage(){
    
    
    
  }
  
  function buildPreview(){
    
    $id = $_GET['id'];
    
    $file_data = $this->fileHandler->getFileData($id);
    
    $src = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'/view';
    $type = $file_data['type'];
    $uploader = $file_data['uploader'];
    $uploader_ip = $file_data['uploader_ip'];
    
    include __DIR__.'/templates/frame_top.php';
    
    if(strpos($type,'image') !== false){
      
      include __DIR__.'/templates/image.php';
      
    } else if(strpos($type,'text') !== false){
      
      include __DIR__.'/templates/text.php';
      
    }else if(strpos($type,'video') !== false){
      
      include __DIR__.'/templates/video.php';
      
    }
    
    include __DIR__.'/templates/frame_bottom.php';

    
    
  }
  
}

?>