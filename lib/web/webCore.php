<?php

/*

@author: Pips

@title: Web Core.
@desc: Class that handles all web-based requests, such as administrator settings and file viewing.

*/
  

class webCore{
  
  protected $settingsHandler;
  protected $userHandler;
  protected $errorHandler;
  protected $fileHandler;
  
  function __construct(){
    
    $this->errorHandler = new errorHandler();
    $this->settingsHandler = new settingsHandler();
    $this->userHandler = new userHandler();
    $this->fileHandler = new fileHandler();
    
    
  }
  
    // process whatever request has come in from a form with POST
  function process(){
    
    $action = $_POST['action'];
    
    // create user
    if( ($action == 'createuser') ){
      
      if(!$this->userHandler->isUser($_POST['username'])){

        $this->userHandler->createUser($_POST['username']);

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
    
      // login
    if ($action == 'login'){
      
      $user_hash = md5($_POST['password']);
        $server_hash = $this->settingsHandler->getSettings()['security']['password_hash'];
        
        if($server_hash === $user_hash){
            $_SESSION['loggedin'] = true;
            $this->buildPage();
            
        }else{
         
            $this->buildPage();
            $this->errorHandler->throwError("action:wrongpassword");
            
        }
      
    }
      
      if($action == 'logout'){
          
          $_SESSION['loggedin'] = false;
          $this->buildPage();
          
      }
    
  }
  
    // should do this
  function buildPage(){
    
    if (!$_SESSION['loggedin']){

      $title = 'login page';
      
        // default header
      include_once __DIR__.'/../templates/admin/default_header.php';
        
        // login page
      include_once __DIR__.'/../templates/admin/login.php';
      
      
    }else{
      
      $title = "Admin Panel";
        
        include_once __DIR__.'/../templates/admin/default_header.php';
        
      include_once __DIR__.'/../templates/admin/main.php';
    }
    
    
  }
  
  
    // this build's the file viewer/preview based on GET headers.
  function buildPreview(){
    
    $id = $_GET['id'];
    $file_data = $this->fileHandler->getFileData($id);
    
    $src = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'/view'; // file source location ( + /view)
    $type = $file_data['type']; // filetype. This will probably need to be fixed later. PHP's $_FILE MIME type is fucked up.
    $uploader = $file_data['uploader']; // the file uploader. Not an object, just a piece of text. 
    $uploader_ip = $file_data['uploader_ip']; // IP of the uploader. 
    $is_admin = $_SESSION['loggedin']; //is admin. This is used in the bottom half of frame.php
    $show_views = $this->settingsHandler->getSettings()['security']['show_views'];
    $show_uploader = $this->settingsHandler->getSettings()['security']['show_views'];
    
    $show = true; // top half, used in frame.php. Need a better way of doing this
        
      include_once __DIR__.'/../templates/admin/default_header.php';
      
      // stupid way of showing the top half and bottom half of the frame.
      include __DIR__.'/../templates/frame/frame.php';
    
    if(strpos($type,'image') !== false){
      
      include __DIR__.'/../templates/viewer/image.php';
      
    } else if(strpos($type,'text') !== false){
      
      include __DIR__.'/../templates/viewer/text.php';
      
    }else if(strpos($type,'video') !== false){
      
      include __DIR__.'/../templates/viewer/video.php';
      
    }
    
   $show = false;
        // stupid way of showing the top half and bottom half of the frame.
        include __DIR__.'/../templates/frame/frame.php';
    
    
  }
    
    
    
  
}

?>