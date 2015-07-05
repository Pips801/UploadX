#!/bin/bash
#Written by gnosticJade - for Apache 2.4.7 / PHP 5.5.9 on Ubuntu 14.04. 
#Should work on any version that keeps the files where it's hardcoded... and yes, I know this is sloppy, but it works.
#If you want to just install it on your own, up post_max_size and upload_max_filesize, and enable mod_rewrite. Also AllowOverride for the domain so .htaccess functions properly.
phpini="/etc/php5/apache2/php.ini"
apacheconf="/etc/apache2/sites-enabled/"


#Remove the lines that contain "post_max_size" and "upload_max_filesize", and replace it with the user's decision of maximum size in the php.ini file's default location.
#This must be changed or UploadX will return domain.ext/index.php whenever a file is too large.
echo "What's the largest file you'd like to let any user upload? eg. 30M for 30 megabytes, or 2G for two gigabytes."
echo -n ">"
read text
sed -i "/upload_max_filesize/c\upload_max_filesize="$text"" "$phpini"
sed -i "/post_max_size/c\post_max_size="$text"" "$phpini"

#replace AllowOverride None (if applicable) with AllowOverride All
echo ""
echo "Which config file would you like to modify? Type the full file name."
echo "Do NOT do this to a file that contains <Directory />"
ls $apacheconf
echo -n ">"
read userInput
echo "You chose: $userInput"
sed -i '/AllowOverride None/c\AllowOverride All' /etc/apache2/sites-available/$userInput

#enable mod_rewrite for apache
a2enmod rewrite
