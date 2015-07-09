<?php

// worry about viewer later
//include __DIR__.'/templates/viewer.php';

/*

[id] 
  - location
  - access_count
  - type
  - uploader
  - uploader_ip
  - delete_after
  
*/

class fileHandler{
  
  protected $settingsHandler;
  protected $errorHandler;
  protected $userHandler;
  
  protected $files;
  
  function __construct(){
    
    $this->settingsHandler = new settingsHandler();
    $this->errorHandler = new errorHandler();
    $this->userHandler = new userHandler();
    
    $this->files = json_decode(file_get_contents(__DIR__.'/files/files.json'), true);
  }
  
  function saveFile($file, $uploader){
    
    // set all the interesting data
    $file_name = $file['name'];
    $file_temp = $file['tmp_name'];
    $ext = pathinfo($file_temp . $file_name, PATHINFO_EXTENSION);
    $file_id = $this->generateFileName();
    $new_file_name = $file_id . '.' . $ext;
    $new_file_location = __DIR__ . $this->settingsHandler->getSettings()['security']['storage_folder'] . $new_file_name;
    
    // create the upload directory if it doesn't exist
    if(!file_exists(__DIR__ . $this->settingsHandler->getSettings()['security']['storage_folder'])){
    mkdir(__DIR__ . $this->settingsHandler->getSettings()['security']['storage_folder']);
}
    // attempt to move the file
    if(move_uploaded_file($file_temp, $new_file_location)){
      
      // bump the count up
      $uploader->uploads++;
      $this->userHandler->saveUser($uploader);
      
      $link_data[$file_id]['location'] = $new_file_location;
      $link_data[$file_id]['access_count'] = -1;
      $link_data[$file_id]['type'] = $file['type'];
      $link_data[$file_id]['uploader'] = $uploader->username;
      $link_data[$file_id]['uploader_ip'] = $_SERVER['REMOTE_ADDR'];
      
       if(isset($_POST['delete']) and ($_POST['delete'] == 'true')){
                
                 $link_data[$file_id]['delete_after'] = true;
                
            }else{
                
                $link_data[$file_id]['delete_after'] = false;
                
            }
      
      $this->files = $this->files + $link_data;
      $this->save();
      
      header("Location: ./$file_id");
      
    }else{
      
      $this->errorHandler->throwError('upload:error');
      
    }
    
  }
  
  function deleteFile($id){
    
    if( $this->isValidId($id) ){
      
      unlink($this->files[$id]['location']);
      unset($this->files[$id]);
      
      $this->save();
      
    }
    
    
  }
  /*
    $link_data[$file_id]['location'] = $new_file_location;
      $link_data[$file_id]['access_count'] = -1;
      $link_data[$file_id]['type'] = $file['type'];
      $link_data[$file_id]['uploader'] = $uploader->username;
      $link_data[$file_id]['uploader_ip'] = $_SERVER['REMOTE_ADDR'];
  
  */
    
    
  function showFile(){  
    
    $id = $_GET['id'];
      $id_data = $this->files[$id];
      $location = $id_data['location'];
      $size = filesize($location);
      $filename = $id ."." . pathinfo($location, PATHINFO_EXTENSION);
      $type = $id_data['type'];
      
      header("Content-type: $type");
      header("Content-legnth: $size");
      header("Connection: Keep-Alive");
      header("Cache-control: public");
      header("Pragma: public");
      header("Expires: Mon, 27 Mar 2038 13:33:37 GMT");
      header("Content-Disposition: inline; filename={$filename}; name={$filename}");
      
      // read the file out
      readfile($location);
      
    
  }
  
  public function isValidId($id){
    
    if(isset($this->files[$id])){
      return true;
    }else{
      return false;
    }
    
  }
  
  function getFileData($id){
    
    if ($this->isValidId($id)){
      
      return $this->files[$id];
      
    }
    else{
      return null;
    }
    
  }
  
  private function generateFileName(){
    
    $generator_settings = $this->settingsHandler->getSettings()['generator'];   
    
    $file_name_mode = $generator_settings['mode'];
    $number_of_chars = $generator_settings['characters'];
    
    $upper_case = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $lower_case = 'abcdefghijklmnopqrstuvwxyz';
    $numeric = '0123456789';
    
    $set;
    
    if($file_name_mode == 1)
        $set = $upper_case . $lower_case . $numeric;
    else if ($file_name_mode == 2)
        $set = $upper_case;
    else if($file_name_mode == 3)
        $set = $lower_case;
    else if($file_name_mode == 4)
        $set = $numeric;
    else{
      $set = $upper_case . $lower_case . $numeric;
    }
        
    $id = substr(str_shuffle($set), 0, $number_of_chars);
    
    if ($this->isValidId($id)){
      
      return $this->generateFileName();
      
    }else{
      
      return $id;
      
    }
    
    
  }
  
  private function save(){
    
    file_put_contents(__DIR__.'/files/files.json', json_encode($this->files, JSON_PRETTY_PRINT));
    $this->files = json_decode(file_get_contents(__DIR__.'/files/files.json'), true);
    
  }
  
  
}



?>