:orphan:

Installing Elgg on XAMPP
########################

These intructions are provided in case you want to test your Elgg
installation on your local computer running Windows.

-  Download and install XAMPP to your computer from 
   http://www.apachefriends.org/en/xampp.html
-  Once the installation is completed, it will prompt you to start the
   XAMPP controller panel. Leave it for now.
-  Open ``C:\xampp\apache\conf\httpd.conf`` file with notepad and uncomment
   these lines:
   
   .. code-block:: ini

     #LoadModule rewrite_module modules/mod_rewrite.so
     #LoadModule filter_module modules/mod_filter.so

-  Edit the php.ini file and change
   ``arg_separator.output = &amp;amp;`` to ``arg_separator.output = &``
-  Go to ``C:\xampp`` and double click on the xampp_start application
-  Go to http://localhost/
-  Change your server's password in the security option
-  Go to http://localhost/phpmyadmin and login with the username and the
   password of your server
-  Create a database called "elgg" in your phpmyadmin panel
-  Now download Elgg. Unzip it and extract to ``C:\xampp\htdocs\sites\elgg``
-  Create the Elgg data folder as ``C:\xampp\htdocs\sites\data``
-  Go to http://localhost/sites/elgg
-  You will be taken to the Elgg installation steps. Install it and enjoy.

A note on XAMPP 1.7.4 and eAccelerator
======================================

Elgg is compatible with opcode caches and it is highly recommended that
you enable a PHP opcode caching tool for a faster experience.  XAMPP comes
with support for eAccelerator out of the box, but unfortunately, the 1.7.4
build of XAMPP leaves out the DLL that's required.  To get eAccelerator
working, follow these steps:

-  Download the DLL from http://eac.qme.nl/eAccelerator_v1_0_svn427_for_v5_3_5-VC6.zip
-  Copy eAccelerator_ts.dll to ``C:\xampp\php\ext\php_eaccelerator.dll``
-  Uncomment this line in ``C:\xampp\php\php.ini``:
   
   .. code-block:: ini
     
     ;zend_extension = "C:\xampp\php\ext\php_eaccelerator.dll"
   
-  Restart apache

To verify that it is on:

-  Go to localhost/xampp
-  Click on phpinfo() from the left sidebar
-  Ctrl+F for eaccelerator.  If you get no results, eAccelerator is not active
