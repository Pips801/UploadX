<?php 

include __DIR__.'/user.php';
include __DIR__.'/settings.php';

class userHandler{
    
    protected $users;
    protected $settings;
    protected $users_json;
    
    function __construct(){
        
        $this->settings = new settings();    
        $this->users = [];
        $this->users_json = json_decode(file_get_contents(__DIR__.'/files/users.json'), true);
        
        foreach ($this->users_json as $username => $settings){
            
            $access_key = $settings['access_key'];
            $filesize_limit = $settings['filesize_limit'];
            $uploads = $settings['uploads'];
            $can_upload = $settings['can_upload'];
            

           $user = new user($username, $access_key, $filesize_limit, $uploads, $can_upload);
            
            array_push($this->users , $user);
        }
        
    }
    
    function createUser($username){
        
        $access_key = $this->generateKey();
        $filesize_limit = $this->settings->getSettings()['limits']['size'];
        $uploads = 0;
        $can_upload = true;
        
        $user = new user($username, $access_key, $filesize_limit, $uploads, $can_upload);
        
        array_push($this->users, $user);
        
        $this->users_json[$username]['access_key'] = $access_key;
        $this->users_json[$username]['filesize_limit'] = $filesize_limit;
        $this->users_json[$username]['uploads'] = $uploads;
        $this->users_json[$username]['can_upload'] = $can_upload;
        
        $this->save();
        
        
    }
    
    private function generateKey(){
    
        $legnth = 6;
        $set = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        return substr(str_shuffle($set), 0, $legnth);
        
    
    }
    
    function getUsers(){
        
        return $this->users;
        
    }
    
    function getUsersAsJson(){
        
        
        return $this->users_json;
        
    }
    
    function save(){
        
        file_put_contents(__DIR__.'/files/users.json', json_encode($this->users_json));
        
    }
    
    function isValidKey($key){
        
        foreach ($this->users as $u){
            
            if( $key == $u->access_key){
                
                return true;
                
            }else{
                
                return false;
            }
            
        }
        
        return false;
        
    }
    
    function getUserByKey($key){
        
        foreach ($this->users as $u){
            
            if( $key == $u->access_key){
                
                return $u;
                
            }else{
                
                return null;
            }
            
        }
        
        return null;
        
    }
    
    
}

?>