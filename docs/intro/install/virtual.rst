:orphan:

Installing Elgg on a Virtual Host
=================================

Examples of "virtual hosts" are Rackspace, Amazon EC2, etc.

For installation to proceed successfully, modify the .htaccess file in the
root, and uncomment:

.. code-block:: apache

    #RewriteBase /

To be:

.. code-block:: apache

    RewriteBase /

Then follow the :doc:`standard installation instructions <../install>`.
