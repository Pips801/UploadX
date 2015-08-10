<div id="main_div">
<h1 class="center_text">Users</h1>
  
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
  </tr>
    <?php 
      foreach ($this->userHandler->getUsers() as $user){
        
        echo ("<tr>");
        
        echo ("<td>". $user->username ."</td>");
        echo ("<td><code>". $user->access_key ."</code></td>");
        echo ("<td>". $user->uploads ."</td>");
        echo ("<td>[Check box]</td>");
        echo ("<td>"); ?>
        <form action='./' method="post">
        
            <input type="submit" value='Delete'>
            <input type="hidden" name="action" value="deleteuser">
          <input type="hidden" name="username" value="<?php echo $user->username?>">
          
        </form> 
    <?php
        
        echo("</td>");
        
        echo ("</tr>");
      }
    
    ?>
    
</table>
  
</div>