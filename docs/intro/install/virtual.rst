:orphan:

Installing Elgg on a Virtual Host
=================================

Examples of "virtual hosts" are Rackspace, Amazon EC2, etc.

For installation to proceed successfully, modify the .htaccess file in the
root, and uncomment::

    #RewriteBase /

To be::

    RewriteBase /

Then follow the :doc:`standard installation instructions <../install>`.