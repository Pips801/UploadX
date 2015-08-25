<div id="main_div">
	<h1 class="center_text"><a class='no_color' href='<?php echo $GLOBALS['home']; ?>admin/'>Settings</a></h1>
  <div class='center_text'><h2>
  <a href="<?php echo $GLOBALS['home'];?>admin/settings/">Settings</a>
  <a href= "<?php echo $GLOBALS['home'];?>admin/users/">Users</a>
  <a href= "<?php echo $GLOBALS['home'];?>admin/uploads/">Uploads</a>
  </h2>
  </div>

  <div id="settings_panel">
    
    
    <div id='settings_main'>
      
      <form method="post" action="./">
      <h2>Viewer</h2>

		  <h3>General</h3>
        
        Show uploader: <input type="checkbox" name="show_uploader"  <?php if ($this->settingsHandler->getSettings()['viewer']['show_uploader']) echo('Checked');?>>
        <br>
        Show views: <input type="checkbox" name="show_views"  <?php if ($this->settingsHandler->getSettings()['viewer']['show_views']) echo('Checked');?>>
        <br>
        Show IP: <input type="checkbox" name="show_ip"  <?php if ($this->settingsHandler->getSettings()['viewer']['show_ip']) echo('Checked');?>>
		  
		  <h3>Video</h3>
		  
		  Loop: <br>
		  Autoplay: <br>
		  Show controls: <br>
		  <br><br>
		  <h2>Config</h2>
        <h3>Files</h3>
        Save location: <input name='save_location' size="15" type="text" value="<?php echo($this->settingsHandler->getSettings()['security']['storage_folder']); ?>">
        <br>
        <h3>Uploads</h3>
        ID generator legnth: <input name='generator_legnth' type="number" size="4" maxlength="2" value="<?php echo($this->settingsHandler->getSettings()['generator']['characters']); ?>">
        <br>
		  
		  
		  <br>
        <input type="submit" value="Save changes">
        <input type="hidden" name="action" value="changesettings">
        <br>
		  
      </form>
		<br><br>
		  <h2>Banned file types</h2> 
		  <table style="width:10%">
		<tr>
		<th>Extension</th>
		<th>Action</th>
		</tr>
		
		  <?php 
        foreach($this->settingsHandler->getSettings()['security']['disallowed_files'] as $value){
          echo ("<tr><td>$value</td><td>delete</td></tr>");
        }
        ?>
			  <tr>
			  <form method="post" action="./">
				  <td>		  
				  <input type="text" placeholder="extension" name="extension" size="5">
				  </td>
				  <td>
				  <input type="submit" value="Add">
				  </td>
			  
				  <input type="hidden" name="action" value="addextension">
			  </form>
			  </tr>
			  
			  </table>
        
      
    </div>
    
    <br><br>
  
    <div id="settings_changepassword">
      
      <h2>Change password</h2>
      
      <form action="./" method="post">

      <input type="password" placeholder="Old password" name="old_password" required>
      <br>
      <br>
      <input type="password" placeholder="New password" name ="new_password" required>
      <br>
      <input type="password" placeholder="Confirm password" name ="confirm_password" required>
              <br>
      Last changed: <?php echo($this->settingsHandler->getSettings()['security']['last_changed']); ?>
      <br>
        <br>
      <input type="submit" value="Change password">
      <input type="hidden" name="action" value="changepassword">

        
      </form>
      
    </div>
    
  </div>
  
</div>

