List of events in core
######################

For more information on how events work visit :doc:`/design/events`.

.. contents:: Contents
   :local:
   :depth: 1

.. note::

	Some events are marked with `(sequence)` this means those events also have a ``:before`` and ``:after`` event
	Also see :ref:`Event sequence <design/events#event-sequence>`

System events
=============

**plugins_load, system** `(sequence)`
    Triggered before the plugins are loaded. Rarely used. init, system is used instead. Can be used to load additional libraries.

**plugins_boot, system** `(sequence)`
    Triggered just after the plugins are loaded. Rarely used. init, system is used instead.

**init, system** `(sequence)`
    Plugins tend to use this event for initialization (extending views, registering callbacks, etc.)

**ready, system** `(sequence)`
	Triggered after the ``init, system`` event. All plugins are fully loaded and the engine is ready
	to serve pages.

**shutdown, system**
    Triggered after the page has been sent to the user. Expensive operations could be done here
    and not make the user wait.

.. note:: Depending upon your server configuration the PHP output
    might not be shown until after the process is completed. This means that any long-running
    processes will still delay the page load.

.. note:: This event is prefered above using ``register_shutdown_function`` as you may not have access
    to all the Elgg services (eg. database) in the shutdown function but you will in the event.

.. note:: The Elgg session is already closed before this event. Manipulating session is not possible.

**regenerate_site_secret:before, system**
    Return false to cancel regenerating the site secret. You should also provide a message
    to the user.

**regenerate_site_secret:after, system**
    Triggered after the site secret has been regenerated.

**log, systemlog**
	Called for all triggered events by ``system_log`` plugin.
	Used internally by ``Elgg\SystemLog\Logger::log()`` to populate the ``system_log`` table.

**upgrade, system**
	Triggered after a system upgrade has finished. All upgrade scripts have run, but the caches 
	are not cleared.

**upgrade:execute, system** `(sequence)`
	Triggered when executing an ``ElggUpgrade``. The ``$object`` of the event is the ``ElggUpgrade``.

**activate, plugin**
    Return false to prevent activation of the plugin.

**deactivate, plugin**
    Return false to prevent deactivation of the plugin.

**init:cookie, <name>**
    Return false to override setting a cookie.

**cache:invalidate, system** `(sequence)`
    Invalidate internal and external caches.
    
**cache:clear, system** `(sequence)`
    Clear internal and external caches, by default including system_cache, simplecache, and memcache. One might use it to 
    reset others such as APC, OPCache, or WinCache.

**cache:purge, system** `(sequence)`
    Purge internal and external caches. This is meant to remove old/stale content from the caches.

**send:before, http_response**
    Triggered before an HTTP response is sent. Handlers will receive an instance of `\Symfony\Component\HttpFoundation\Response` 
    that is to be sent to the requester. Handlers can terminate the event and prevent the response from being sent by returning `false`.

**send:after, http_response**
    Triggered after an HTTP response is sent. Handlers will receive an instance of `\Symfony\Component\HttpFoundation\Response` 
    that was sent to the requester.

**reload:after, translations**
    Triggered after the translations are (re)loaded.

User events
===========

**login:before, user**
    Triggered during login. Returning false prevents the user from logging

**login:after, user**
	Triggered after the user logs in.

**login:first, user**
    Triggered after a successful login. Only if there is no previous login.

**logout:before, user**
    Triggered during logout. Returning false should prevent the user from logging out.

**logout:after, user**
	Triggered after the user logouts.

**validate, user**
    When a user registers, the user's account is disabled. This event is triggered
    to allow a plugin to determine how the user should be validated (for example,
    through an email with a validation link).

**validate:after, user**
    Triggered when user's account has been validated.

**invalidate:after, user**
    Triggered when user's account validation has been revoked.

**profileupdate, user**
    User has changed profile

**profileiconupdate, user**
    User has changed profile icon

**ban, user**
    Triggered before a user is banned. Return false to prevent.

**unban, user**
    Triggered before a user is unbanned. Return false to prevent.

**make_admin, user**
	Triggered before a user is promoted to an admin. Return false to prevent.

**remove_admin, user**
	Triggered before a user is demoted from an admin. Return false to prevent.

Relationship events
===================

**create, relationship**
    Triggered after a relationship has been created. Returning false deletes
    the relationship that was just created.

**delete, relationship**
    Triggered before a relationship is deleted. Return false to prevent it
    from being deleted.

**join, group**
    Triggered after the user ``$params['user']`` has joined the group ``$params['group']``.

**leave, group**
    Triggered before the user ``$params['user']`` has left the group ``$params['group']``.

Entity events
=============

**create, <entity type>**
    Triggered for user, group, object, and site entities after creation. Return false to delete entity.

**update, <entity type>**
    Triggered before an update for the user, group, object, and site entities. Return false to prevent update.
    The entity method ``getOriginalAttributes()`` can be used to identify which attributes have changed since
    the entity was last saved.

**update:after, <entity type>**
    Triggered after an update for the user, group, object, and site entities.
    The entity method ``getOriginalAttributes()`` can be used to identify which attributes have changed since
    the entity was last saved.

**delete:before, <entity type>**
    Triggered before entity deletion. Return false to prevent deletion.

**delete, <entity type>**
    Triggered before entity deletion.

**delete:after, <entity type>**
    Triggered after entity deletion.

**disable, <entity type>**
    Triggered before the entity is disabled. Return false to prevent disabling.

**disable:after, <entity type>**
	Triggered after the entity is disabled.

**enable, <entity type>**
    Return false to prevent enabling.

**enable:after, <entity type>**
	Triggered after the entity is enabled.

Metadata events
===============

**create, metadata**
    Called after the metadata has been created. Return false to delete the
    metadata that was just created.

**update, metadata**
    Called after the metadata has been updated. Return false to *delete the metadata.*

**delete, metadata**
    Called before metadata is deleted. Return false to prevent deletion.

**enable, metadata**
	Called when enabling metadata. Return false to prevent enabling.

**disable, metadata**
	Called when disabling metadata. Return false to prevent disabling.

Annotation events
=================

**annotate, <entity type>**
    Called before the annotation has been created. Return false to prevent
    annotation of this entity.

**create, annotation**
    Called after the annotation has been created. Return false to delete
    the annotation.

**update, annotation**
    Called after the annotation has been updated. Return false to *delete the annotation.*

**delete, annotation**
    Called before annotation is deleted. Return false to prevent deletion.

**enable, annotation**
	Called when enabling annotations. Return false to prevent enabling.

**disable, annotations**
	Called when disabling annotations. Return false to prevent disabling.

River events
============

**create:before, river**
	Called before the river item is saved to the database. Return ``false`` to prevent the item from being created. 

**create:after, river**
	Called after a river item is created.

**delete:before, river**
	Triggered before a river item is deleted. Returning false cancels the deletion.

**delete:after, river**
	Triggered after a river item was deleted.

File events
===========

**upload:after, file**
    Called after an uploaded file has been written to filestore. Receives an
    instance of ``ElggFile`` the uploaded file was written to. The ``ElggFile``
    may or may not be an entity with a GUID.

Notes
=====

Because of bugs in the Elgg core, some events may be thrown more than once
on the same action. For example, ``update, object`` is thrown twice.
