Services
########

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
---------------

An instance of ``Elgg\Services\ConfigInterface``, this is for getting and setting various system
configuration values.

.. note::

    If you have a useful idea, you can :doc:`add a new service </contribute/services>`!
