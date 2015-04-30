<?php 

## Security key
#
# The key that allows you to upload. This key and the key set inside ShareX needs to be the same for the upload to work. Change this to whatever you want.
$key = "CHANGEME";

## The number of characters to use when generating a file name.
#
# The more users you have/files you push, the higher this number should be. 
# If you're using alphanumeric characters ($file_name_mode = 1), and set the number of characters to 4, that means you have 14,776,336 file possibilities before you run out.
# Changing this will not effect other files. All previous links to files will work.
# recommended: 3-5.
$number_of_chars = 4;

# The mode that the script generates file names.
#
# Changing this will not effect other files. All previous links to files will work.
# 1 - Use random alphanumeric characters. A-Z, a-z, and 0-9. This has 62^x (x being number of chars) possible generations before it runs out. This has the most.
# 2 - Use only uppercase letters. A-Z. This has 26^x (x being number of chars) possible generations before it runs out.
# 3 - Use only lowercase letters. a-z. This has 26^x (x being number of chars) possible generations before it runs out.
# 4 - Use only numbers. 0-9. This has 10^x (x being number of chars) possible generations before it runs out. This has the least.
$file_name_mode = 1; 

## Rename Attempts
#
# The ammount of times the script tries to rename the file if it already exists. This does not need to be that high unless you have a lot of files.
# Do not change this unless you understand the use of it.
$rename_attempts = 20;

## Debug mode.
#
# Enable this if you want to see debug features, including the PHP features. 
# This is only needed if there are errors saving the file, with name generation, etc.
$debug = false;

## Show upload page
#
# Show the upload page in the web browser when they attempt to access the index page.
# This page is mainly used to test the server without ShareX.
$show_upload_page = false;

##No page message
#
# message to show when $show_upload_page is disabled.
$no_page_message = "<br><br><center>This is a <a href='https://getsharex.com/'>ShareX</a> proxy.<br>Source can be found <a href='https://github.com/PixelPips/UploadX'>here</a>.</center>";

//check if there's a file and if the key sent is the actual key.
if(isset($_FILES['file']) and ($_POST['key'] == $key)){
    
    // if debug is enabled, show PHP errors in the error log.
    if($debug){
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }
    
    //DO NOT TOUCH
    $file = $_FILES['file']; // get the file
    $file_name = $_FILES['file']['name']; // get the file name
    $file_temp_loc = $_FILES['file']['tmp_name']; // get the temp location of the file
    $file_extension = pathinfo($file_temp_loc . $file_name, PATHINFO_EXTENSION); // get the extention of the file
    $old_file_name = $file_temp_loc ."/". $file_name;
    $new_file_name = generateID() .'.'. $file_extension;
  
    $times_renamed = 0;
    while(file_exists($new_file_name) and ($times_renamed < $rename_attempts)){
    
        $new_file_name = generateID() . '.' . $file_extension;
        $times_renamed++;
    }
    
    if($times_renamed < $rename_attempts){
        
        if(move_uploaded_file($file_temp_loc, "./" . $new_file_name)){

            header( 'Location: ./' . $new_file_name) ;

        }else{
          if($debug){
            echo("There was an error saving the file. dumping data<br>");
            echo("\$file_name = $file_name <br>
            \$file_temp_loc = $file_temp_loc <br>
            \$file_extention = $file_extention <br>
            \$old_file_name  = $old_file_name <br>
            \$new_file_name = $new_file_name <br>
            \$key = {$_POST['key']} <br>
            \$file_name_mode = $file_name_mode <br>
            \$number_of_chars = $number_of_chars <br>
            \$rename_attempts = $rename_attempts <br>
            \$show_upload_page = $show_upload_page <br>
            ");
          }
        }
    }
    else{
        echo("Couldn't generate a file name that wasn't taken. <br>Tried: {$rename_attempts} times. <br>Increase the attempts (\$rename_attempts), increase the number of characters to generate (\$number_of_chars), or change the file naming mode. (\$file_name_mode.)");
    }
        
}else{
  
  if($show_upload_page){
    echo '<form action="./" method="post" enctype="multipart/form-data"><input type="file" name="file"><br>key: <input type="password" name="key"><br><input type="submit" value="upload" name="submit"></form>';

  }else{
    echo ($no_page_message);
  }
}

function generateID(){

    global $number_of_chars,$file_name_mode;

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
      if($debug)
        echo ("\$file_name_mode is not valid. Defaulting to alphanumeric name generation<br>");
      
      $set = $upper_case . $lower_case . $numeric;
    }
        
    return substr(str_shuffle($set), 0, $number_of_chars);
}

?>