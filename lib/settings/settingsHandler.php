<?php

/*

@author: Pips

@title: Settings Handler.
@desc: Class that manages UploadX settings, such as security.

*/



class settingsHandler{
    
    protected $settings;
    
    function __construct(){
        
        $this->settings = json_decode(file_get_contents(__DIR__.'/../files/settings.json'), true);
        
    }
    
    // return ALL the settings. 
    function getSettings(){
        
        return $this->settings;
        
    }
  
    // should do this
  function changeSetting($level, $setting, $newValue){
    
    if(isset($this->settings[$level][$setting])){
      
      $this->settings[$level][$setting] = $newValue;
      
      file_put_contents(__DIR__.'/../files/settings.json', json_encode($this->settings, JSON_PRETTY_PRINT));
      
      $this->settings = json_decode(file_get_contents(__DIR__.'/../files/settings.json'), true);
      
    }
    
  }
    
}

?>