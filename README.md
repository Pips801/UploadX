this code was hard to write
so it should be hard to read

It's a mess, I know, but the goal was a single file.

DONE:
- json file that stores data that can be edited by the user via the script
- web panel for 
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
- either re-generate or download from the internet a replacment data.json
- in admin panel allow settings to be changed
- check if password hash is still the default password. if it is, then prompt to change it
- double check password when changing
- style the shit out of it
