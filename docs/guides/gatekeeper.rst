Gatekeeper
==========

Gatekeeper functions allow you to manage how code gets executed by applying access control rules.

.. _elgg-gatekeeper:

elgg_gatekeeper()
-----------------

This function will forward a user to the front page if the current viewing user is not logged in.

This can be used in your plugin's pages to protect them from being viewed by non-logged in users.

.. note::

   In versions of Elgg prior to 1.9 this function was called ``gatekeeper()``

elgg_admin_gatekeeper()
-----------------------

Same as :ref:`elgg-gatekeeper` , but ensures that only admin users can view the page.

.. note::

   In versions of Elgg prior to 1.9 this function was called ``admin_gatekeeper()``

action_gatekeeper()
-------------------

This function should be used in :doc:`actions` , and helps protect the action from certain forms of attack.

.. note::

   As of Elgg version 1.8 this function is called for all registered actions. There is no longer a need to call this function in your own actions. If you wish to protect other pages with action tokens then you can call this function.
