<div id="main_div">
<h1 class="center_text">Settings</h1>
  
  <div id="settings_panel">
    
    
    <div id='settings_main'>
      
      <form method="post" action="./">
      <h2>Configuration</h2>
      <h3>Viewer</h3>
        
        Show uploader: <input type="checkbox" name="show_uploader"  <?php if ($this->settingsHandler->getSettings()['security']['show_uploader']) echo('Checked');?>>
        <br>
        Show views: <input type="checkbox" name="show_views"  <?php if ($this->settingsHandler->getSettings()['security']['show_views']) echo('Checked');?>>
        <br>
        Show IP: <input type="checkbox" name="show_ip"  <?php if ($this->settingsHandler->getSettings()['security']['show_ip']) echo('Checked');?>>
        <br>
        <h3>Files</h3>
        Save location: <input name='save_location' size="15" type="text" value="<?php echo($this->settingsHandler->getSettings()['security']['storage_folder']); ?>">
        <br>
        <h3>Uploads</h3>
        ID generator legnth: <input name='generator_legnth' type="number" size="4" maxlength="2" value="<?php echo($this->settingsHandler->getSettings()['generator']['characters']); ?>">
        <br>
        Banned file extensions: <?php 
        foreach($this->settingsHandler->getSettings()['security']['disallowed_files'] as $value){
          echo ($value . ", ");
        }
        ?>
        <br><br>
        <input type="submit" value="Save changes">
        <input type="hidden" name="action" value="changesettings">
        
      </form>
      
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

