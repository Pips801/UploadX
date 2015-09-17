<?php

/*

@author: Pips

@title: Web Core.
@desc: Class that handles all web-based requests, such as administrator settings and file viewing.

*/


class webCore
{
    
    protected $settingsHandler;
    protected $userHandler;
    protected $errorHandler;
    protected $fileHandler;
    
    function __construct()
    {
        
        $this->errorHandler    = new errorHandler();
        $this->settingsHandler = new settingsHandler();
        $this->userHandler     = new userHandler();
        $this->fileHandler     = new fileHandler();
        
        
    }
    
    // process whatever request has come in from a form with POST
    function process()
    {
        
        # change this into a switch-case
        
        $action = $_POST['action'];
        
        // create user
        if (($action == 'createuser')) {
            
            $this->userHandler->createUser($_POST['username']);
            $this->refreshPage();
            
        } 
		
		else if ($action == 'addextension') {
            
            $this->settingsHandler->addExtension($_POST['extension']);
            $this->refreshPage();
            
        }
		
		else if ($action == 'deleteextension'){
			
			$this->settingsHandler->deleteExtension($_POST['extension']);
			$this->refreshPage();
		}
        
        else if ($action == 'changekey') {
            
            $this->userHandler->changeKey($_POST['username'], $_POST['key']);
            $this->refreshPage();
            
        }
        
        else if ($action == 'newkey') {
            
            $this->userHandler->newKey($_POST['username']);
            $this->refreshPage();
            
        }

        else if ($action == 'deleteuser') {
            
            $this->userHandler->deleteUser($_POST['username']);
            $this->refreshPage();
            
        }
        
        else if ($action == 'login') {
            
            $user_hash = md5($_POST['password']);
            $server_hash = $this->settingsHandler->getSettings()['security']['password_hash'];
            
            if ($server_hash === $user_hash) {
                
                $_SESSION['loggedin'] = true;
                $this->refreshPage();
                
            } else {
                
                $this->refreshPage();
                
            }
            
        }
        
        else if ($action == 'logout') {
            
            $_SESSION['loggedin'] = false;
            $this->refreshPage();
            
        }
        
        else if ($action == 'changepassword') {
            
            $this->settingsHandler->changePassword($_POST['old_password'], $_POST['new_password'], $_POST['confirm_password']);
			
			
        } 
		
		else if ($action == 'changesettings') {
            
            // we have these weird if statments to check the state of a checkbox. It doesn't reuturn true or false, it returns "checked" and null. Thanks, html.
            if (!isset($_POST['show_uploader']))
                $_POST['show_uploader'] = false;
            else
                $_POST['show_uploader'] = true;
            
            if (!isset($_POST['show_views']))
                $_POST['show_views'] = false;
            else
                $_POST['show_views'] = true;
            
            if (!isset($_POST['show_ip']))
                $_POST['show_ip'] = false;
            else
                $_POST['show_ip'] = true;
            
            $this->settingsHandler->changeSetting('viewer', 'show_uploader', $_POST['show_uploader']);
            $this->settingsHandler->changeSetting('viewer', 'show_views', $_POST['show_views']);
            $this->settingsHandler->changeSetting('viewer', 'show_ip', $_POST['show_ip']);
            
            $this->settingsHandler->changeSetting('security', 'storage_folder', $_POST['save_location']);
            
            $this->settingsHandler->changeSetting('generator', 'characters', $_POST['generator_legnth']);
            
            $this->refreshPage();
            
        }
        
        else if ($action == "deletefile") {
            
            $this->fileHandler->deleteFile($_POST['id']);
            $this->refreshPage();    
            
        }
        
		else if ($action == 'generatejson'){
			
			$this->userHandler->generateJson($_POST['username']);
			
		}
        
        else {
            echo "unknown action: $action";
        }
		
		# refresh page
        
    }
    
    // should do this
    function buildPage($page, $option)
    {
        
        include_once __DIR__ . '/../templates/admin/default_header.php';
        
        if (!$_SESSION['loggedin']) {
            
            $title = 'Login page';
            
            
            // default header
            
            
            // login page
            include_once __DIR__ . '/../templates/admin/login.php';
            
            
            // logic for admin page managment
            
        } else {
            
            if ($page == 'home') {
                
                $title = "Admin Panel";
                include_once __DIR__ . '/../templates/admin/main.php';
            } else if ($page == 'settings') {
                
                $title = 'Settings';
                include_once __DIR__ . '/../templates/admin/settings.php';
                
            } else if ($page == 'users') {
                
                $title = 'Users';
                include_once __DIR__ . '/../templates/admin/users.php';
                
            } else if ($page == 'uploads') {
                
                $title = 'Uploads';
                include_once __DIR__ . '/../templates/admin/uploads.php';
                
            }
        }
        
        
    }
    
    
    // this build's the file viewer/preview based on GET headers.
    function buildPreview()
    {
        
        $id          = $_GET['id'];
        $file_data   = $this->fileHandler->getFileData($id);
        $views       = $file_data['access_count'];
        $src         = $GLOBALS['home'] . $id . '/view'; // file source location ( + /view)
        $type        = $file_data['type']; // filetype. This will probably need to be fixed later. PHP's $_FILE MIME type is fucked up.
        $uploader    = $file_data['uploader']; // the file uploader. Not an object, just a piece of text. 
        $uploader_ip = $file_data['uploader_ip']; // IP of the uploader. 
        $is_admin    = $_SESSION['loggedin']; //is admin. This is used in the bottom half of frame.php
        
        $show = true; // top half, used in frame.php. Need a better way of doing this
        
        include_once __DIR__ . '/../templates/admin/default_header.php';
        
        // stupid way of showing the top half and bottom half of the frame.
        include __DIR__ . '/../templates/frame/frame.php';
        
        if (strpos($type, 'image') !== false) {
            
            include __DIR__ . '/../templates/viewer/image.php';
            
        } else if (strpos($type, 'text') !== false) {
            
            include __DIR__ . '/../templates/viewer/text.php';
            
        } else if (strpos($type, 'video') !== false) {
            
            include __DIR__ . '/../templates/viewer/video.php';
            
        } else if (strpos($type, 'audio') !== false) {
            
            include __DIR__ . '/../templates/viewer/audio.php';
            
        }
        
        else {
            include __DIR__ . '/../templates/viewer/unknown.php';
        }
        
        $show = false;
        // stupid way of showing the top half and bottom half of the frame.
        include __DIR__ . '/../templates/frame/frame.php';
        
        
    }
    
    function refreshPage()
    {
        
        header("Location: ./");
        
    }
    
    
    
    
}

?>