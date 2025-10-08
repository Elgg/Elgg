Restore capability
##################

.. contents:: Contents
   :local:
   :depth: 2

As of Elgg 6.0 it's possible to set the ``restorable`` :doc:`capability </guides/capabilities>` on an ``ElggEntity``.
Enabling this capability will mark an entity as deleted in the database when the ``ElggEntity::delete()`` function is called.
The entity will then no longer show up in listings or work when viewing it directly.

Site setting
------------

A site administrator has the option to enable/disable all restore features. By default this feature is disabled. This
means that even if an entity has the capability ``restorable`` it will always be permanently removed from the database.

Registration
------------

Just like any other entity capability you can enable the ``restorable`` capability in the ``elgg-plugin.php``

.. code-block:: php

	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'my_custom_subtype',
			'capabilities' => [
				'restorable' => true,
			],
		],
	]

Entity menu
-----------

By default a menu item is added to the entity menu which allows a user to delete the entity when the user has the rights
to do so.

If an entity has the ``restorable`` capability this menu item will be replaced with a menu item which will mark the entity
as deleted.

.. note::

	When the site administrator hasn't enabled the feature no menu items will be replaced.

.. note::

	There are 2 generic actions to help developers in case they need to add a delete link somewhere.

	- ``entity/delete``: this will permanently delete the entity from the database, requires a ``guid`` to be submitted to the action
	- ``entity/trash``: this will mark the entity as deleted in the database, requires a ``guid`` to be submitted to the action

View deleted items
------------------

Once an entity has been marked as deleted it'll no longer show up in the normal functionality of your Elgg website.

In order for a user to see the entities that have been deleted there is a link in the user settings to a list of all
deleted items that are owned by the given user.

Group owners also have the ability to see the deleted content from their group. This is accessible from the group profile
page. The list will show all deleted content contained by their group.

.. note::

	The list will only show the deleted entities with the ``restorable`` capability. For example when a blog has been
	deleted which also has comments	only the blog will show up in the deleted list of the owner (and in the deleted
	list of the group if the blog was posted in a group).

	The comments will not show up in any list of deleted items.

Custom views
============

When a developer needs to have a custom view of a deleted item a view ``trash/<entity_type>/<entity_subtype>`` can be made
which will get provided the deleted entity in ``$vars['entity']``. As a fallback ``trash/<entity_type>/default`` will be
tried and ultimately ``trash/entity/default`` which is provided by Elgg core.

Different sub-elements can be found in the views ``trash/elements/*``.

.. note::

	When making a custom view for an entity make sure it doesn't include links to the deleted entity as that link will
	not work. Also keep in mind other links to entities that could have been deleted.

Restore a deleted item
----------------------

From the deleted list the user (or group owner) has the ability to restore the deleted item to it's original state. If
the entity was contained in a group which was removed, the user has the option to restore the entity to a different container.

Events
------

When an entity is being marked as deleted there is an :ref:`event sequence<event-sequence>` ``'trash', '<entity_type>'``
with which a developer can program additional action or logic.

ElggEntity functions
--------------------

There are 3 functions in an ``ElggEntity`` related to the deletion of that entity:

- ``public function delete(bool $recursive = true, ?bool $persistent = null): bool``
- ``protected function persistentDelete(bool $recursive = true): bool``
- ``protected function trash(bool $recursive = true): bool``
- ``public function isDeleted(): bool``

Function: delete
================

This is the only public function to delete an entity. The ``$recursive`` parameter will determine whether or not other entities
which have this entity as it's owner or container will also be deleted (default ``true``).

The ``$persistent`` parameter can force a persistent removal from the database or it being marked as deleted. The default
value is ``null`` which means the ``restorable`` capability will be checked.

.. warning::

	It's not recommended that a developer overrules this function as the developer will have to handle part of the logic
	of determining the correct value of the ``$persistent`` parameter.

Function: persistentDelete
==========================

This function is called when the ``$persistent`` parameter is ``true`` in the ``delete()`` function. This function must
handle cases where the entity is permanently removed from the database. An example of when a developer would overrule this
function is an ``ElggFile`` where the physical file on disk needs to be removed when the entity is removed from the database,
but the physical file shouldn't be removed from the disk when the entity is only marked as deleted in the database.

This will trigger the ``'delete', '<entity_type>'`` event sequence.

Function: trash
===============

This function is called when the ``$persistent`` parameter is ``false`` in the ``delete()`` function. This function must
handle cases where the entity is marked as deleted in the database.

This will trigger the ``'trash', '<entity_type>'`` event sequence.

Function: isDeleted
===================

To check if an entity is marked as deleted.

Show deleted items
------------------

When a developer needs to be sure to include deleted entities when fetching/listing entities the code needs to be wrapped
in an ``elgg_call()`` with the flag ``ELGG_SHOW_DELETED_ENTITIES``.

The same applies when the developer needs to be sure to exclude all deleted items set the flag ``ELGG_HIDE_DELETED_ENTITIES``.

Cleanup of deleted entities
---------------------------

In order to cleanup the database of the deleted entities a :doc:`cron job</guides/cron>` runs every hour. It'll cleanup
all the deleted entities that have been removed when a retention period has passed. A site administrator can set this
retention period (default: 30 days).

In order to not put too much stress on the system the cron job will only run for a maximum of 5 minutes per hour. Entities
that couldn't be removed in that period will be removed in the next period. The oldest deleted entity (by when the entity
was deleted) will be removed first.

More information
----------------

.. seealso::

	Check out the :doc:`/guides/capabilities` documentation
