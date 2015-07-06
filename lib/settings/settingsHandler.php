<?php

/*

@author: Pips

@title: Settings Handler.
@desc: Class that manages UploadX settings, such as security.

@todo: Actually change settings.

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
  function changeSetting($setting, $newValue){
    
    
    
  }
    
}

?>