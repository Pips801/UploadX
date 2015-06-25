<?php

class uploadHandler{
    
    protected $userHandler;
    protected $errorHandler;
    protected $settings;
    
    function __construct(){
        
        $this->errorHandler = new errorHandler();
        $this->userHandler = new userHandler();
        $this->settings = new settings();
        
    }
    
    function process(){
        
        if (empty($_FILES)){
            
            
            
        }
        
        
    }
    
    function checkForUpload(){
        
        if(isset($_FILES['file'])){
            return true;
        }else{
            return false;
        }
        
    }
    
    
}

?>