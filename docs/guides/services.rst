Services
########

Elgg uses the ``Elgg\Application`` class to load and bootstrap Elgg. In future releases this
class will offer a set of service objects for plugins to use.

.. note::

    If you have a useful idea, you can :doc:`add a new service </contribute/services>`!

Menus
-----

``elgg()->menus`` provides low-level methods for constructing menus. In general, menus should be
passed to ``elgg_view_menu`` for rendering instead of manual rendering.
