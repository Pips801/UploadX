<div id='info_panel'>
<?php 

$show_uploader = $this->settingsHandler->getSettings()['viewer']['show_uploader'];
$show_views = $this->settingsHandler->getSettings()['viewer']['show_views'];
$show_ip = $this->settingsHandler->getSettings()['viewer']['show_ip']; // this should probably always be false. when enabled, it will show the uploaders IP to ANYONE.

$loggedin = $_SESSION['loggedin'];


if($loggedin){

  echo ("Uploader: $uploader<br>
        Views: $views<br>
        IP: $uploader_ip");
  
}else{
  
  if($show_uploader){
    echo ("Uploader: $uploader<br>");
  }
  
  if($show_views){
     echo ("Views: $views<br>");
  }
  
  if ($show_ip){
    echo(" IP: $uploader_ip (This feature should only be enabled for development)");
  }
  
}


?>
</div>