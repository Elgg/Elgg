List of events in core
######################

.. contents:: Contents
   :local:
   :depth: 1

System events
=============

**boot, system**
    First event triggered. Triggered before plugins have been loaded.

**plugins_boot, system**
    Triggered just after the plugins are loaded. Rarely used. init, system is used instead.

**init, system**
    Plugins tend to use this event for initialization (extending views, registering callbacks, etc.)

**ready, system**
	Triggered after the ``init, system`` event. All plugins are fully loaded and the engine is ready
	to serve pages.

**pagesetup, system**
    Called just before the first content is produced. Is triggered by ``elgg_view()``.

**shutdown, system**
    Triggered after the page has been sent to the user. Expensive operations could be done here
    and not make the user wait.

.. note:: Depending upon your server configuration the PHP output
    might not be shown until after the process is completed. This means that any long-running
    processes will still delay the page load.

**regenerate_site_secret:before, system**
    Return false to cancel regenerating the site secret. You should also provide a message
    to the user.

**regenerate_site_secret:after, system**
    Triggered after the site secret has been regenerated.

**log, systemlog**
	Called for all triggered events. Used internally by ``system_log_default_logger()`` to populate
	the ``system_log`` table.

**upgrade, system**
	Triggered after a system upgrade has finished. All upgrade scripts have run, but the caches 
	are not cleared.

**upgrade, upgrade**
	A single upgrade script finished executing. Handlers are passed a ``stdClass`` object with the properties
		* from - The version of Elgg upgrading from.
		* to - The version just upgraded to.

**activate, plugin**
    Return false to prevent activation of the plugin.

**deactivate, plugin**
    Return false to prevent deactivation of the plugin.

**init:cookie, <name>**
    Return false to override setting a cookie.

**cache:flush, system**
    Reset internal and external caches, by default including system_cache, simplecache, and memcache. One might use it to reset others such as APC, OPCache, or WinCache.

User events
===========

**login:before, user**
    Triggered during login. Returning false prevents the user from logging

**login:after, user**
	Triggered after the user logs in.

**logout:before, user**
    Triggered during logout. Returning false should prevent the user from logging out.

**logout:after, user**
	Triggered after the user logouts.

**validate, user**
    When a user registers, the user's account is disabled. This event is triggered
    to allow a plugin to determine how the user should be validated (for example,
    through an email with a validation link).

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

**create, <relationship>**
    Triggered after a relationship has been created. Returning false deletes
    the relationship that was just created.

**delete, <relationship>**
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

**update:after, <entity type>**
    Triggered after an update for the user, group, object, and site entities.

**delete, <entity type>**
    Triggered before entity deletion. Return false to prevent deletion.

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

**created, river**
	Called after a river item is created.

Notes
=====

Because of bugs in the Elgg core, some events may be thrown more than once
on the same action. For example, ``update, object`` is thrown twice.
