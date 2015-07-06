<?php 

/*

@author: Pips

@title: User Handler
@desc: class that handles user creation and permission checking.

*/

class userHandler{
    
    protected $users;
    protected $settings;
    protected $users_json;
    
    function __construct(){
        
        // create settings handler to check user creation agaist settings
        $this->settingsHandler = new settingsHandler();    
        
        // users array
        $this->users = [];
        
        // users array as raw JSON from file
        $this->users_json = json_decode(file_get_contents(__DIR__.'/../files/users.json'), true);
        
        // loop through each user and create it, then add it to the $users array
        foreach ($this->users_json as $username => $settings){
            
            $access_key = $settings['access_key'];
            $filesize_limit = $settings['filesize_limit'];
            $uploads = $settings['uploads'];
            $enabled = $settings['enabled'];
            

           $user = new user($username, $access_key, $filesize_limit, $uploads, $enabled);
            
            array_push($this->users , $user);
        }
        
    }

    // create user. Should add support to limit uploads. later.
    function createUser($username){
        
        $access_key = $this->generateKey();
        $filesize_limit = $this->settingsHandler->getSettings()['limits']['size'];
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
    
  // this is a way that we can update a user's settings; by recreating the uesr out-of-class, then passing it here.
  function saveUser($user){
    
    $this->users_json[$user->username]['access_key'] = $user->access_key;
    $this->users_json[$user->username]['filesize_limit'] = $user->filesize_limit;
    $this->users_json[$user->username]['uploads'] = $user->uploads;
    $this->users_json[$user->username]['enabled'] = $user->enabled;
    
    $this->save();
    
  }
    
    // this just saves the json data to the fime.
    function save(){
        
        file_put_contents(__DIR__.'/../files/users.json', json_encode($this->users_json, JSON_PRETTY_PRINT));
        
    }
    
        private function generateKey(){
    
        $legnth = 6;
        $set = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        return substr(str_shuffle($set), 0, $legnth);
        
    
    }
    
    // return the array of users.
    function getUsers(){
        
        return $this->users;
        
    }
    
    // returns the json array of users. 
    function getUsersAsJson(){
        
        
        return $this->users_json;
        
    }
  
    // delete a user by his/her username.
  function deleteUser($username){
    
    if ($this->isUser($username)){
      
      unset ($this->users_json[$username]);
      
      $this->save();
      
    }
    
  }
    
    // check if the given key is a valid upload key.
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
  
    // return the user associated to the username
    function getUser($username){
      
      if ($this->isUser($username)){
        
            foreach ($this->users as $u){
            
              if( $username == $u->username ){

                  return $u;

              }else{

                  return null;
              }
            
        }
        
      }
      
    }
  
    // check if the given username is an actual user.
    function isUser($username){
      
      foreach ($this->users as $u){
            
            if( $username == $u->username ){
                
                return true;
                
            }else{
                
                return false;
            }
            
        }
      
    }
    
    // return the user that has the given key.
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