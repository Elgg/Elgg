:orphan:

Installing Elgg on EasyPHP
##########################

You should first be familiar with the :doc:`standard installation instructions <../install>`.

-  Assuming no MySQL, PHP or Apache installations exist already.
-  Best run as a development/test server

1. Stop IIS running if installed

2. Download and install the latest Easy PHP from http://www.easyphp.org

3. Set up the database and point the web server to your Elgg folder (all done from the EasyPHP tray icon) 
   -  Right click EasyPHP tray icon, select "Administration"
   -  A new tab is created in your browser for managing Easy PHP
   -  Add your Elgg folder to Apache in "Alias" section
   -  Click "Manage MySQL with PhpMyAdmin", create a database and account for Elgg

4. (Ignore this step for v5.3 or later) From the tray icon go Configuration/Apache
   and uncomment this line:
   
   .. code-block:: ini
   
     #LoadModule rewrite_module modules/mod_rewrite.so

5. (Ignore this step for v5.3 or later) Change ``AllowOverride None`` to ``AllowOverride All``
   in the relevant directory entry in Configuration/Apache 

6. (Ignore this step for v5.3 or later) From the tray icon fo Configuration/PHP
   and uncomment this line:
   
   .. code-block:: ini
   
     ;extension=php_curl.dll

7. A reboot is best Elgg should run via http://127.0.0.1
