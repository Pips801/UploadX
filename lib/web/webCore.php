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
      
       //$this->buildPage("users", "");
      
      $this->refreshPage();
    }
    
    // delete user
    else if ($action == 'deleteuser'){
      
      if($this->userHandler->isUser($_POST['username'])){
        
        $this->userHandler->deleteUser($_POST['username']);
        
        
        
      }else{
        
        $this->errorHandler->throwError('action:nouser');
        
      }
      
      //$this->buildPage("users", "");
      
      $this->refreshPage();
      
    }
    
      // login
   else if ($action == 'login'){
      
      $user_hash = md5($_POST['password']);
        $server_hash = $this->settingsHandler->getSettings()['security']['password_hash'];
        
        if($server_hash === $user_hash){
            $_SESSION['loggedin'] = true;
          
            //$this->buildPage('home', null);
          
          $this->refreshPage();
            
        }else{
         
            //$this->buildPage('home', null);
            $this->refreshPage();
          
            $this->errorHandler->throwError("action:wrongpassword");
            
        }
      
    }
      
      else if($action == 'logout'){
          
          $_SESSION['loggedin'] = false;
        
          //$this->buildPage('home', null);
      $this->refreshPage();
          
      }
    
    else if($action == 'settings'){
      
      include_once __DIR__.'/../templates/admin/default_header.php';
      include_once __DIR__.'/../templates/admin/settings.php';
    }
    
    
    //
    // TODO: replace this with buildpage/refresh
    //
    
    else if($action == 'changepassword'){
      
      if( md5($_POST['old_password']) === $this->settingsHandler->getSettings()['security']['password_hash'] ){
        
        if($_POST['new_password'] === $_POST['confirm_password']){
          
         $this->settingsHandler->changeSetting('security', 'password_hash', md5($_POST['new_password']));
          $this->settingsHandler->changeSetting('security', 'last_changed', date("m-d-y"));
          
          include_once __DIR__.'/../templates/admin/default_header.php';
          $message = 'Password changed.';
          include_once __DIR__.'/../templates/display/notification.php';
          include_once __DIR__.'/../templates/admin/settings.php';
          
          
        }else{
          
          include_once __DIR__.'/../templates/admin/default_header.php';
          $message = 'Passwords do not match.';
          include_once __DIR__.'/../templates/display/notification.php';
          include_once __DIR__.'/../templates/admin/settings.php';
          
        }
        
      }else{
        
      include_once __DIR__.'/../templates/admin/default_header.php';
          $message = 'Wrong password. Logging you out for security reasons.';
          include_once __DIR__.'/../templates/display/notification.php';
          $_SESSION['loggedin'] = false;
        $this->buildPage('main', null);
      }
      
    // admin is changing settings.
    }else if ($action == 'changesettings'){
      
      // we have these weird if statments to check the state of a checkbox. It doesn't reuturn true or false, it returns "checked" and null. Thanks, html.
       if(!isset($_POST['show_uploader']))
                $_POST['show_uploader'] = false;
            else
                $_POST['show_uploader'] = true;
      
      if(!isset($_POST['show_views']))
                $_POST['show_views'] = false;
            else
                $_POST['show_views'] = true;
      
      if(!isset($_POST['show_ip']))
                $_POST['show_ip'] = false;
            else
                $_POST['show_ip'] = true;
      
      $this->settingsHandler->changeSetting('security', 'show_uploader', $_POST['show_uploader']);
      $this->settingsHandler->changeSetting('security', 'show_views', $_POST['show_views']);
      $this->settingsHandler->changeSetting('security', 'show_ip', $_POST['show_ip']);
      
      $this->settingsHandler->changeSetting('security', 'storage_folder', $_POST['save_location']);
      
      $this->settingsHandler->changeSetting('generator', 'characters', $_POST['generator_legnth']);
      
      include_once __DIR__.'/../templates/admin/default_header.php';
        $message = 'Your changes have been saved.';
        include_once __DIR__.'/../templates/display/notification.php';
        include_once __DIR__.'/../templates/admin/settings.php';
    }
    
    //admin is viewing users
    else if ($_POST['action'] == "deletefile"){
      
      $this->fileHandler->deleteFile($_POST['id']);
      
      $message = 'File deleted';
        include_once __DIR__.'/../templates/display/notification.php';
      
      //$this->buildPage("uploads", null);
      $this->refreshPage();
      
      
    }
    
    
    else{
      echo "unknown error";
    }
    
  }
  
    // should do this
  function buildPage($page, $option){
    if (!$_SESSION['loggedin']){

      $title = 'Login page';
          include_once __DIR__.'/../templates/admin/default_header.php';
        // default header
      
        
        // login page
      include_once __DIR__.'/../templates/admin/login.php';
      
      
      // logic for admin page managment
      
    }else{
      
      if($page == 'home'){
      
      $title = "Admin Panel";
            include_once __DIR__.'/../templates/admin/default_header.php';
      include_once __DIR__.'/../templates/admin/main.php';
      }
      else if($page == 'settings'){
        
        $title = 'Settings';
        include_once __DIR__.'/../templates/admin/default_header.php';
        include_once __DIR__.'/../templates/admin/settings.php';
        
      }else if($page == 'users'){
        
        $title = 'Users';
        include_once __DIR__.'/../templates/admin/default_header.php';
        include_once __DIR__.'/../templates/admin/users.php';
        
      }
      else if($page == 'uploads'){
        
        $title = 'Uploads';
        include_once __DIR__.'/../templates/admin/default_header.php';
        include_once __DIR__.'/../templates/admin/uploads.php';
        
      }
    }
    
    
  }
  
  
    // this build's the file viewer/preview based on GET headers.
  function buildPreview(){
    
    $id = $_GET['id'];
    $file_data = $this->fileHandler->getFileData($id);
    $views = $file_data['access_count'];
    $src = $GLOBALS['home'].$id.'/view'; // file source location ( + /view)
    $type = $file_data['type']; // filetype. This will probably need to be fixed later. PHP's $_FILE MIME type is fucked up.
    $uploader = $file_data['uploader']; // the file uploader. Not an object, just a piece of text. 
    $uploader_ip = $file_data['uploader_ip']; // IP of the uploader. 
    $is_admin = $_SESSION['loggedin']; //is admin. This is used in the bottom half of frame.php
    
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
      
    }else if(strpos($type,'audio') !== false){
      
      include __DIR__.'/../templates/viewer/audio.php';
      
    }
    
    else{
      include __DIR__.'/../templates/viewer/unknown.php';
    }
    
   $show = false;
        // stupid way of showing the top half and bottom half of the frame.
        include __DIR__.'/../templates/frame/frame.php';
    
    
  }
  
  function refreshPage(){
    
    header("Location: ./");
    
  }
    
    
    
  
}

?>