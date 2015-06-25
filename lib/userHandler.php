<?php 

include __DIR__.'/user.php';

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
            $enabled = $settings['enabled'];
            

           $user = new user($username, $access_key, $filesize_limit, $uploads, $enabled);
            
            array_push($this->users , $user);
        }
        
    }
    
    function createUser($username){
        
        $access_key = $this->generateKey();
        $filesize_limit = $this->settings->getSettings()['limits']['size'];
        $uploads = 0;
        $enabled = true;
        
        $user = new user($username, $access_key, $filesize_limit, $uploads, $enabled);
        
        array_push($this->users, $user);
        
        $this->users_json[$username]['access_key'] = $access_key;
        $this->users_json[$username]['filesize_limit'] = $filesize_limit;
        $this->users_json[$username]['uploads'] = $uploads;
        $this->users_json[$username]['enabled'] = $enabled;
        
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
  
  function saveUser($user){
    
    $this->users_json[$user->username]['access_key'] = $user->access_key;
    $this->users_json[$user->username]['filesize_limit'] = $user->filesize_limit;
    $this->users_json[$user->username]['uploads'] = $user->uploads;
    $this->users_json[$user->username]['enabled'] = $user->enabled;
    
    $this->save();
    
  }
    
    function save(){
        
        file_put_contents(__DIR__.'/files/users.json', json_encode($this->users_json, JSON_PRETTY_PRINT));
        
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
        
      if($this->isValidKey($key)){
      
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
}

?>