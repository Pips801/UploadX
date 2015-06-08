<?php

/*
this code was hard to write
so it should be hard to read

It's a mess, I know , but the goal was a single file.

DONE:
    - json file that stores data that can be edited by the user via the script
    - regenerate json datafile and .htaccess file when the script is first started or the files are deleted.
    - web panel for 
    - security
        - admin password preventing users from modifying settings
        - users
        - user access keys, so each user gets a key that they use to let them upload
        - filesize limit, to prevent too large of files (not implimented yet)
        - enable and disable accounts
        - sessions
    - administration
        - create accounts
        - set the maximum file size allowed per user and globaly (not implimented yet)
        - enable and disable accounts
        - delete accounts
        - view number of files uploaded for each user
        - change administrator password

TODO:
    - check file size
    - either re-generate or download from the internet a replacment data.json
    - in admin panel allow settings to be changed
    - check if password hash is still the default password. if it is, then prompt to change it
    - double check password when changing
    - style the shit out of it
    
*/

// regenerate the datafile
if(!file_exists('./data.json')){
    
    file_put_contents('./data.json', '{"Settings":{"number_of_chars":4,"file_name_mode":1,"rename_attempts":20,"debug":false,"index_message":"This is a development ShareX Proxy script. This message should be changed before releasing.","default_upload_limit":50,"PW_hash":"5f4dcc3b5aa765d61d8327deb882cf99"},"Users":[]}');
    
    echo('datafile was missing, restored from default. Password is <b>password</b>, please change it.');
    
}

// create the htaccess file to protect the datafile
if(!file_exists('./.htaccess')){
    
    file_put_contents('./.htaccess', '<Files "data.json">
Order Allow,Deny
Deny from all
</Files>
<Files "links.json">
Order Allow,Deny
Deny from all
</Files>
RewriteEngine on  
RewriteCond %{REQUEST_FILENAME} !-f  
RewriteCond %{REQUEST_FILENAME} !-d  
RewriteRule ^(.*)$ ./index.php?id=$1 [L,QSA]');
    
}

if(!file_exists('./links.json')){
     file_put_contents('./links.json', '{}');
}

if(!file_exists('./uploads/')){
    mkdir('./uploads/');
}

// grab json data from the file.
$json = json_decode(file_get_contents("data.json"), true);
$links = json_decode(file_get_contents("links.json"), true);
    

/*

LOGIN ACTIONS

*/

// start a session
session_start();

// check if a session has been staretd and if the user is logged in
if (!isset($_SESSION['loggedIn']) and (!isset($_FILES['file']))){
    
    // check the action
    if(isset($_POST['action'])){
        
        // check the action (again)
        if($_POST['action'] == 'login'){
            
            // compare md5
            if(md5($_POST['password']) === $json['Settings']['PW_hash']){
                
                //log user in
                $_SESSION['loggedIn'] = true;
                
                // refresh the page to show info
                refresh();
                
            }else{
                refresh();
            }
            
        }
        
        // user is not logged in, show login page.
    }else{
        
        ?>

<form action="./" method="post" autocomplete="off" novalidate>

    <input type="password" name="password" required>
    <input type="submit" name="submit" value="Log in">
    <input type=hidden name="action" value="login">

</form>

<?php
        
    }
    
/*    

FILE HANDLING

*/  
    
}else if (isset($_FILES['file'])){
    
    $upload_ok = true;
    $error_message;
    
    //submit checks
    
    if (!isset($_POST['key'])){
        $upload_ok = false;
        $error_message = "Key not submitted.";
    }
    
    if (!is_valid_key($_POST['key'])){
        $upload_ok = false;
        $error_message = "Invalid key.";
    }
        
    $user = get_user_by_key($_POST['key']);
    
    // user checks
    
    if (! $json['Users'][$user]['can_upload'] ){
        $upload_ok = false;
        $error_message = "You are not allowed to upload.";
    }
    
    // set some vars
    $file_name = $_FILES['file']['name'];
    $file_temp_name = $_FILES['file']['tmp_name'];
    $extension = pathinfo($file_temp_name . $file_name, PATHINFO_EXTENSION);
    
    $file_id = generate_file_name();
    
    $new_file_name = $file_id . "." . $extension;
    
    $new_file_location = './uploads/' . $new_file_name;
    
    // show error
    if($upload_ok){
    
    // attempt to create/move the file
        if(move_uploaded_file($file_temp_name, $new_file_location)){

            //update users upload count 
            $json['Users'][$user]['uploads']++;
            $links[$file_id]['actual_file'] = $new_file_location;
            $links[$file_id]['uploader'] = $user;
            $links[$file_id]['times_accessed'] = 0;
            save_json();

            // redirect to the file
            header( 'Location: ./' . $file_id);

        }else{
            $upload_ok = false;
            $error_message = "There was an error saving the file.";
        }
    
    }
            
    

} 
else if (isset($_GET['id'])){
    
    if(isset($links[$_GET['id']])){
    
    $links[$_GET['id']]['times_accessed']++;
    save_json();
    
    echo ("<center><img src='{$links[$_GET['id']]['actual_file']}'><br>Uploader: {$links[$_GET['id']]['uploader']}<br>Views: {$links[$_GET['id']]['times_accessed']}<center>");
    }else{
        echo("That file does not exist.");
    }
    
    
}


else{

/*

POST ACTIONS

*/
    
    // check if there's a submitted action
    if(isset($_POST['action'])){
        
        
        // admin is creating a user
        if($_POST['action'] == "create"){
            
            // create the user with POST data
            create_user($_POST['user'], $_POST['upload_size']);
            
            // tell them we created it
            echo("Created user <b>{$_POST['user']}</b>");
        
        }
        
        
         // admin is deleting user
        else if($_POST['action'] == "delete"){
            
            // delete the user
            delete_user($_POST['user']);
            
            // tell them such
            echo("Deleted user <b>{$_POST['user']}</b>");

        }
        
        
        // admin is editing a user
        else if($_POST['action'] == 'edit'){
            
            // input toggle boxes are stupid.
            // instead of returning true or false for checked or unchecked, it returns "on" and null.
            // the fuck?
            if(!isset($_POST['can_upload']))
                $_POST['can_upload'] = false;
            else
                $_POST['can_upload'] = true;
            
            // edit the user
            edit_user($_POST['user'], $_POST['upload_size'], $_POST['can_upload']);
            
        }
        
        
        // admin  is changing password
        else if ($_POST['action'] == 'changepw'){
            
            if(change_password($_POST['old_password'], $_POST['new_password']))
                echo ("Password changed.");
            else
                echo ("Wrong password.");
            
        }
        

        // log the admin out
        else if($_POST['action'] == "logout"){
            
            // null the session data
            session_destroy();

            // refresh the page to take them to the login screen
            refresh();

        }

    }
    
    ?>

<center><h1>Users</h1></center>

    <table border="1" style="width:100%">
        
        <tr style="">
            
            <th>Username</th>
            <th>Access key</th>
            <th>Uploaded files</th>
            <th>filesize limit</th>
            <th>Can Upload</th>
            <th>Save</th>
            <th>Delete user</th>
            
        </tr>
        
<?php

    // loop through the users
foreach(get_users() as $user){
    
    
    ?>
 <tr>

    <td>
        <?php echo $user; ?>
    </td>

    <td>
        <code>
            <?php echo $json['Users'][$user]['access_key']; ?>
        </code>
    </td>

    <td>
        <?php echo $json[ 'Users'][$user][ 'uploads']; ?>
    </td>

     <form action="./" method="post">
    <td>
         <select name="upload_size">
             <option value="<?php echo $json[ 'Users'][$user][ 'filesize_limit']; ?>">
                 <?php if ($json[ 'Users'][$user][ 'filesize_limit'] == 0){ echo "Unlimited"; }else{ echo $json[ 'Users'][$user][ 'filesize_limit'] . 'MB'; } ?>
             </option>
            <option value="0">Unlimited</option>
            <option value="default"> default ( <?php echo $json[ 'Settings'][ 'default_upload_limit']?>MB)</option>
            <option value="10">10MB</option>
            <option value="20">20MB</option>
            <option value="30">30MB</option>
            <option value="40">40MB</option>
            <option value="50">50MB</option>
            <option value="60">60MB</option>
            <option value="70">70MB</option>
            <option value="80">80MB</option>
            <option value="90">90MB</option>
            <option value="100">100MB</option>

        </select>
        
    </td>
    <td>Can upload  <input type="checkbox" name="can_upload"  <?php if ($json[ 'Users'][$user][ 'can_upload']) echo('Checked');?>>
    </td>
         
    <td>
        <input type="submit" value="save" name="save">
        <input type="hidden" name="action" value="edit">
        <input type="hidden" name="user" value="<?php echo $user; ?>">
    </td>
         </form>

    <form action='./' method='post'>
        <td>
            <input type='submit' name='submit' value='delete'>
        </td>

        <input type='hidden' name='action' value='delete'>

        <input type='hidden' name='user' value='<?php echo $user ?>'>

    </form>

</tr>
    
    <?php
    
}

?>
        
    </table>

<h1>Create a user</h1>
    <form action="./" method="post" enctype="multipart/form-data">
        username:
        <input type="text" name="user" required="">
        <br>upload limit:

        <select name="upload_size">
            
             <option value="0">Unlimited</option>
            <option value="default"> default (
                <?php echo $json[ 'Settings'][ 'default_upload_limit']?>MB)</option>
            <option value="10">10MB</option>
            <option value="20">20MB</option>
            <option value="30">30MB</option>
            <option value="40">40MB</option>
            <option value="50">50MB</option>
            <option value="60">60MB</option>
            <option value="70">70MB</option>
            <option value="80">80MB</option>
            <option value="90">90MB</option>
            <option value="100">100MB</option>

        </select>
        <br>
        <input type="submit" value="CREATE" name="submit">
        <input type="hidden" name="action" value="create">
    </form>


<h1>Change admin password</h1>

<?php
    
    if($json['Settings']['PW_hash'] === '5f4dcc3b5aa765d61d8327deb882cf99')
        echo '<h3 style="color:red">Your password is password. Please change it.</h3>';
    ?>

<form action="./" method="post">
    Old password:
    <input type="password" name='old_password' required>
<br>
    New password:
    <input type="password" name="new_password" required>
    <br>
    <input type="submit" name="submit" value="change password">
    <input type="hidden" name="action" value="changepw">
</form>
<br>
<br>
<form action="./" method="post">

    <input type="submit" name="logout" value="logout">
    <input type="hidden" name="action" value="logout">

</form>

<?php
    

}

/*

FUNCTIONS

*/


//generate an access key that's 6 characters long.
function generate_key(){
    
    $legnth = 6;
    $set = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    
     return substr(str_shuffle($set), 0, $legnth);
    
}

//create the user
function create_user($user_name, $max_filesize){
    
    global $json;
    
    //check for default filesize limit
    if($max_filesize == "default")
        // set it to the default
        $max_filesize = $json["Settings"]["default_upload_limit"];
    
    // collect and save the user data to create
    $json["Users"][$user_name] = [
        "access_key" => generate_key(),
        "filesize_limit" => $max_filesize,
        "uploads" => 0,
        "can_upload" => true
    ];
        
        // save the new json data with the new user
        save_json();
    
}

// delete user
function delete_user($user_name){
    
    global $json;
    
    // unset the user
    unset($json["Users"][$user_name]);
    
    // save the new json file
    save_json();
    
}

// get all the users
function get_users(){
    
    global $json;
    
    $users = [];
    
    // loop through the current users
    foreach($json["Users"] as $key => $value){
        
        // add them to an array
        array_push($users, $key);
        
    }
    
    return($users);
    
}

// edit a user
function edit_user($user_name, $max_filesize, $can_upload){
    
    global $json;
    
    $json['Users'][$user_name]['filesize_limit'] = $max_filesize;
    $json['Users'][$user_name]['can_upload'] = $can_upload;
    
    save_json();
    
}

// save the new JSON file. 
function save_json(){
    
    global $json;
    global $links;
    
    file_put_contents('data.json', json_encode($json));
    file_put_contents('links.json', json_encode($links));
    
}

function change_password($old_password, $new_password){
    
    global $json;
    
    if (md5($old_password) === $json['Settings']['PW_hash']){
        
        $json['Settings']['PW_hash'] = md5($new_password);
        
        save_json();
        return true;
        
    }
    return false;
    
}

function is_valid_key($key){
    
    global $json;
    
    $valid_keys = [];
    
    foreach(get_users() as $user){
        
        array_push($valid_keys, $json['Users'][$user]['access_key']);
        
    }
    
    if (in_array($key, $valid_keys))
        return true;
    else
        return false;
    
}

function get_user_by_key($key){
    
    global $json;
    
    $user_from_key = null;
    
    foreach(get_users() as $user){
        
        if ($json['Users'][$user]['access_key'] == $key){
            $user_from_key = $user;
        }
    }
    
    return $user_from_key;

    
}

// generate a file name based off settings
function generate_file_name(){
    global $json;
    
    $file_name_mode = $json['Settings']['file_name_mode'];
    $number_of_chars = $json['Settings']['number_of_chars'];
    
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
        
    return substr(str_shuffle($set), 0, $number_of_chars);
}


// refresh the current page
function refresh(){
    
    header('Location: ./');
    
}

?>