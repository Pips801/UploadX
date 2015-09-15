#TODO
A general page to help me or any other developer figure out what is done, reference code, or fix shit.

###GENERAL
* Update error handling
* Find some way to make it so POSTed pages can display a message/status

###FEATURES
* URL shortening (should be pretty easy)

###SETTINGS
* Video player controls should be made or removed

###USERS
* Enabled button needs to do something
* Delete buttons need to be coded
* JSON generator needs made

##SECURITY
* Better password security than hashing.

###UPLOADS
* Better sorting
* Add page support so it isn't flodding 
* Thumbnail (for use in Uploads viewer and in future updates to support REGEX from ShareX)

#POST['action'] actions
A reference sheet to let you know what the valid post actions are.

###USER
* `createuser`  
  * args: `username`
* `deleteuser`
  * args: `username`
* `generatejson` (not wirtten)
  * args: `username`
* `enableuser` (not written)
  * args: `username`
* `disableuser` (not written)
  * args: `username`

###KEY
* `changekey`
  * args: `username`, `key`
* `newkey`
  * args: `username`

###SESSION
* `login`
  * args: `password`
* `logout`

###SECURITY
* `changepassword`
  * args: `oldpassword`, `newpassword`, `confirmpassword`
* `changesettings`
  * args: `show_uploader`, `show_views`, `show_ip`, `save_location`, `generator_legnth` (not finished)
* `addextension`
  * args: `extension`
* `deleteextension`
  * args: `extension`

###UPLOADS
* `deletefile`
  * args: `id`
* `deleteuploads` (not written)
  * args: `username`
* `deleteuploadsanduser` (not written)
  * args: `username`
