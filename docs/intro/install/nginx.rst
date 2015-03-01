:orphan:

Installing Elgg on Nginx
########################

To run Elgg on Nginx, you will need to:

-  configure Nginx to talk to a PHP process in either CGI or FPM mode
-  Port the following rewrite rules

.. literalinclude:: ../../../install/config/nginx.dist

Other than that, you should be able to follow the :doc:`standard installation instructions <../install>`.