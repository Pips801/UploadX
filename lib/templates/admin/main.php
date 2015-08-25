<div id="main_div">

  <h1 class="center_text">UploadX Administrator Panel</h1>
<div class='center_text'><h2>
  <a href="<?php echo $GLOBALS['home'];?>admin/settings/">Settings</a>
  <a href= "<?php echo $GLOBALS['home'];?>admin/users/">Users</a>
  <a href= "<?php echo $GLOBALS['home'];?>admin/uploads/">Uploads</a>
  </h2>
  </div>


  <form action="./" method="post" class='center_text'>

    <input type="submit" value="log out">
    <input type="hidden" name="action" value="logout">

  </form>


</div>