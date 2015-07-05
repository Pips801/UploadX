## Installation
You have two options when it comes to installing and setting up UploadX. You can do it via the install.sh (in this repo), or you can do it manually. If you choose to do it manually...

1.  Enable mod_rewrite - sudo a2enmod rewrite
2.  Up your post_max_size and upload_max_filesize, both in php.ini. This prevents UploadX from returning http://domain.ext/index.php because the limit isn't high enough.
3.  AllowOverride for the domain/folder - so htaccess works and data is private.
4.  [Configure ShareX](https://github.com/PixelPips/UploadX/wiki/Client-Installation-and-Configuration).
5.  You're done!

## Features
DONE:
- json file that stores data that can be edited by the user via the script
- web panel for administraton
- regenerate htaccess, links.json and data.json
- yay! now, at night, you have the option to detect the **server**'s time and apply css accordingly!
- password security: it will yell at you if your password is `password`
- security
    - admin password preventing users from modifying settings
    - users
    - user access keys, so each user gets a key that they use to let them upload
    - filesize limit, to prevent too large of files (not implimented yet)
    - enable and disable accounts
    - sessions
- administration
    - create accounts
    - set the maximum file size allowed per user and globaly (not allowed)
    - enable and disable accounts
    - delete accounts
    - view number of files uploaded for each user
    - change administrator password

TODO:
- check file size
- in admin panel allow settings to be changed

- double check password when changing
- style the shit out of it
