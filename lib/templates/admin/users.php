<div id="main_div">
	<h1 class="center_text"><a class='no_color' href='<?php echo $GLOBALS['home']; ?>admin/'>Users</a></h1>
  <div class='center_text'><h2>
  <a href="<?php echo $GLOBALS['home'];?>admin/settings/">Settings</a>
  <a href= "<?php echo $GLOBALS['home'];?>admin/users/">Users</a>
  <a href= "<?php echo $GLOBALS['home'];?>admin/uploads/">Uploads</a>
  </h2>
  </div>

	
	
  <form action="./" method="post">
    
    <h2>Create a user</h2>
    
    Username: <input name="username" type="text" placeholder="new username" required>
    <br>
    <input type="submit" value="Create">
    <input type="hidden" name="action" value="createuser">
  
  </form>
    
  <h2>View and edit users</h2>
  
  <table style="width:100%;">
  <tr>
    <th>Username</th>
    <th>Access key</th>
    <th>Uploads</th>
    <th>Enabled</th>
	 <th>Delete</th>
	 <th>Custom uploader JSON</th>
  </tr>
    <?php 
      foreach ($this->userHandler->getUsers() as $user){
        
        ?>
    
    <tr>

      <td><?php echo "<a href='". $GLOBALS['home'] . 'admin/uploads/' . $user->username ."' alt='View Uploads from user'>" . $user->username . '</a>'; ?></td>
      <td>
		  <form action="./" method="post">
			  <input type="text" name="key" value="<?php echo $user->access_key; ?>">
			  <input type="hidden" name="action" value="changekey">
			  <input type="hidden" name="username" value="<?php echo $user->username; ?>">
		  </form>
		  
		  <form action="./" method="post">
				<input type="submit" value="&#8635">
				<input type="hidden" name="action" value="newkey">
				<input type="hidden" name="username" value="<?php echo $user->username; ?>">
		  </form>
			  
	  </td>
      <td><?php echo $user->uploads; ?></td>
      <td>
		  <form action="./" method="post">
		
			<input onChange="this.form.submit()" type="checkbox" name="enabled" <?php if($user->enabled) echo 'checked'  ?>> Enable account
			<input type="hidden" name="action" value="enable">
			<input type="hidden" name="username" value="<?php echo $user->username; ?>">
			
		
		</form>
		</td>
      <td>
        <form action="./" method="post">
        
          <input type="submit" value="Delete">
          <input type="hidden" name="action" value="deleteuser">
          <input type="hidden" name="username" value="<?php echo $user->username; ?>">
			
			&nbsp;&nbsp;&nbsp;
		  </form>
			<form action="./" method="post">
			
				<input type="submit" value="Delete Uploads">
				<input type="hidden" name="action" value="deleteuploads"> 
			
			</form>
			
          
        
      </td>
		<td>
		<form action="./" method="post">
		
			<input type="submit" value="Generate">
			<input type="hidden" name="action" value="generatejson">
			<input type="hidden" name="username" value="<?php echo $user->username; ?>">
		
		</form>
		</td>
	  </tr>
		
      <?php
        
      }
    
    ?>
    
</table>
  
</div>