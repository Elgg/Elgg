Services
########

.. contents:: Contents
   :local:
   :depth: 2

Accessing Services
==================

Elgg uses the ``Elgg\Application`` class to load and bootstrap Elgg, but this class also offers
a set of service objects for plugins to use.

Plugins can access the application instance via the global function ``elgg()``, from which
services are available via property access. E.g. these are equivalent:

.. code:: php

    $foo = elgg_get_config('foo');

    $foo = elgg()->config->get('foo');

Should I modify existing plugin code to use services?
-----------------------------------------------------

This is not necessary. Pre-existing global functions like ``elgg_get_config()`` are *not*
deprecated and will be supported at least through Elgg 2.x. If they will reduce your maintenance
burden of supporting older Elgg versions, we encourage you to continue using them in your plugins.

Service: config
===============

An instance of ``Elgg\Services\Config``, this is for getting and setting various system
configuration values.

Service: env
============

An instance of ``Elgg\Services\Environment``, this is for determining the environment in which
the site is running, via methods ``getName()`` and ``isProd()``.

The environment can be configured in your settings.php file by setting ``$CONFIG->elgg_env`` to
an array with keys:

- **name**: (string) the Name of the site instance
- **is_prod**: (bool) ``true`` if the instance is in production, else ``false``

Alternately you may provide an anonymous function that returns an implementation of
``Elgg\Services\Environment``.

.. warning::

    You cannot read ``$CONFIG->elgg_env`` or ``elgg_get_config('elgg_env')`` in your plugins. Use
    the ``elgg()->env`` service.

.. note::

    The environment values can be seen by admins on the page ``/admin/statistics/server/``.

Contribute a new service
========================

If you have a useful idea, you can :doc:`add a new service </contribute/services>`!
