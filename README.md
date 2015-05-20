# UploadX

This work is protected by the DBAD (Don't Be A Dick) License. http://www.dbad-license.org

UploadX - A PHP-based ShareX proxy for simple file uploading. This script allows you to easily upload your files from ShareX to your server, instead of the other public hosts.


## Installation

  1. Copy `index.php` to the directory where it will be run, such as `/yoursite/uploads/`.
  2. You're done. That was easy.
  
  
## Configuration

### Server
UploadX is very straight-forward, just open up `index.php` in a text editor of your choice. All of the configuration settings are explained at the top of the file.

  1. `$key`. This is what allows you to upload from the client. Change this to something secure enough that somebody can't randomly guess. This doesn't have to be anything too secure, as it is in plain text.
  2. `$number_of_chars`. Defaults to 4. This is the number of characters the script will generate for a file name. The more users you have, the higher this should be.
  3. `$file_name_mode`. Leave this setting alone if you don't know what it does. It's better explained in the file comments.
  4. `$no_page_message`. This is the message displayed when you access the index page without a filename.
  
#### Debug settings
These do NOT need to be changed at all unless you know what you're doing.

  1. `rename_attempts`. The number of times the script will attempt to rename the file if the file name it generated is already taken.
  2. `$debug`. Enables debug settings.
  3. `$show_upload_page`. You can use this for debugging. On the index page, it will show a simple HTML form that allows you to test your uploading without being configured.
  

### Client
You should probably install ShareX. That's pretty important.

  1. Open the ShareX window.
  2. click `Destinations` on the left
  3. Open the settings. ![](https://i.imgur.com/8Og0DLk.png)
  4. Scroll to the bottom.
  5. click `Custom uploaders`.
  6. Name your uploader.
  7. Set `Request URL:` to the location of the script.
  8. Set `File from name` to `file`.
  9. Create an argument, with the name as `key`, and the Value to whatever `$key` is in your configurations of the script. Press `add`.
  11. Make sure that `Response type` is set to `Redirection URL`.
  12. On the bottom left, make sure all uploaders are set to your new proxy.
  13. (optional) Press the `test` button next to your uploader. It should give you a link.
  
It should look something like this.
![](https://i.imgur.com/LOIdRyt.png)
  
You're done!
