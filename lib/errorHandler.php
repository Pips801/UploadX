<?php

include __DIR__.'/error.php'; // include errors so we can construct the error

class errorHandler{
    
    protected $error;
    
    public function __construct(){

    }

    // we get the errors dynamically instead once per load
    public function getError($error_code){
        
        $error_codes = json_decode(file_get_contents(__DIR__.'/files/errors.json'), true); // get the errors

        if(isset($error_codes[$error_code])){
            
            $error = new error($error_codes[$error_code]);
            
            return $error; 

        }

    }
    
    public function throwError($error_code){
        
        $error = $this->getError($error_code);
        
        include(__DIR__.'/templates/error_message.php');
        
    }
}

?>