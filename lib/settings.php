<?php

class settings{
    
    protected $settings;
    
    function __construct(){
        
        $this->settings = json_decode(file_get_contents(__DIR__.'/files/settings.json'), true);
        
    }
    
    function getSettings(){
        
        return $this->settings;
        
    }
    
}

?>