:orphan:

Installing Elgg on IIS
######################

You can follow the :doc:`standard installation instructions <../install>` with the
following caveats:

When installing on IIS, the problem is that the Apache mod\_rewrite
rules will not be recognized, and this breaks the application. You need
to convert the mod\_rewrite rules to the `IIS URL Rewrite`_ module
format.

You can do this using the IIS 7+ management console, and the "Import
Rules" feature that will do the conversion, as described in the tutorial
"`importing Apache mod\_rewrite rules`_\ ".

.. _IIS URL Rewrite: http://www.iis.net/download/URLRewrite
.. _importing Apache mod\_rewrite rules: http://learn.iis.net/page.aspx/470/importing-apache-modrewrite-rules/

