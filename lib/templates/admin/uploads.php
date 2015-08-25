<div id='main_div'>
	<h1 class="center_text"><a class='no_color' href='<?php echo $GLOBALS['home']; ?>admin/'>Uploads</a></h1>
  <div class='center_text'><h2>
  <a href="<?php echo $GLOBALS['home'];?>admin/settings/">Settings</a>
  <a href= "<?php echo $GLOBALS['home'];?>admin/users/">Users</a>
  <a href= "<?php echo $GLOBALS['home'];?>admin/uploads/">Uploads</a>
  </h2>
  </div>

	
  <h3>View uploads from user</h3>
  <select onChange="window.location.href=this.value">
    <option value="">Select user</option>
    <?php
    
$users = $this->userHandler->getUsers();
    
foreach ($users as $user){
  
 echo ("<option value='".$GLOBALS['home']."admin/uploads/". $user->username ."'>". $user->username ."</option>");
  
}

    ?>
    
</select><br><br>
  
  <table style="width:100%;">
  <tr>
    <th>ID</th>
    <th>Uploader</th>
    <th>Uploader IP</th>
    <th>Views</th>
    <th>File MIME type</th>
    <th>Original filename</th>
    <th>Delete</th>
  </tr>
  
  <?php
$uploads = $this->fileHandler->getJsonData();
$upload_count = count($uploads);+94


if (!empty($_GET['opt'])){
  
  $user = $_GET['opt'];
  
  if($this->userHandler->isUser($user)){
    
    $new_uploads = [];
    
    foreach ($uploads as $key => $data){
      
      if( $data['uploader'] == $user){
        $new_uploads[$key] = $data;
      }
    }
    
    $uploads = $new_uploads;
    
    echo("<h3 class='center_text'>Showing uploads for user \"<u>$user</u>\"</h3>");
    
  }
  
}

foreach ($uploads as $key => $data){
  echo("<tr>");
  
  echo ("<td><a href='". $GLOBALS['home'].$key ."'>$key</td>");
  echo ("<td>". $data['uploader'] ."</td>");
  echo ("<td>". $data['uploader_ip'] ."</td>");
  echo ("<td>". $data['access_count'] ."</td>");
  echo ("<td>". $data['type'] ."</td>");
  echo ("<td>". $data['old_name'] ."</td>");
  echo ("<td>");
  

  ?>
    <form action="./" method="post">
    
      <input type="submit" value="Delete file">
      <input type="hidden" name="action" value="deletefile">
      <input type="hidden" name="id" value="<?php echo $key?>">
    
    </form>
    <?php
  echo("</td>");
  
  echo("</tr>");
}
  ?>
  
</div>