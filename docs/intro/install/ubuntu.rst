:orphan:

Installing Elgg on Ubuntu Linux
===============================

-  Install the dependencies:

   .. code-block:: sh
   
     sudo apt-get install apache2
     sudo apt-get install mysql-server
     sudo apt-get install php5 libapache2-mod-php5 php5-mysqlnd
     sudo apt-get install phpmyadmin
     sudo a2enmod rewrite

-  Edit ``/etc/apache2/sites_available/default`` to enable .htaccess processing (set AllowOverride to All)
-  Restart Apache: ``sudo /etc/init.d/apache2 restart``
-  Follow the :doc:`standard installation instructions <../install>`
