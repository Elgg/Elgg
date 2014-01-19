List of events in core
######################

.. toctree::
   :maxdepth: 2

System events
=============

**boot, system**
    first event triggered. Triggered before plugins have been loaded.

**plugins_boot, system**
    triggered just after the plugins are loaded. Rarely used. init, system is used instead.

**init, system**
    plugins tend to use this event for initialization (extending views, registering callbacks, etc.)

**ready, system**

**pagesetup, system**
    called just before the first content is produced. Is triggered by elgg_view().

**shutdown, system**
    triggered after the page has been sent to the user. Expensive operations could be done here
    and not make the user wait. Note: Depending upon your server configuration the PHP output
    might not be shown until after the process is completed. This means that any long-running
    processes will still delay the page load.

**log, systemlog**

**upgrade, system**

**upgrade, upgrade**

**activate, plugin**
    return false to prevent activation of the plugin.

**deactivate, plugin**
    return false to prevent deactivation of the plugin.

**init:cookie, <name>**
    return false to override setting a cookie.

User events
===========

**login, user**
    triggered during login. Returning false prevents the user from logging

**logout, user**
    triggered during logout. Returning false should prevent the user from logging out.

**validate, user**
    when a user registers, the user's account is disabled. This event is triggered
    to allow a plugin to determine how the user should be validated (for example,
    through an email with a validation link).

**profileupdate, user**
    user has changed profile

**profileiconupdate, user**
    user has changed profile icon

**ban, user**
    return true to ban user

**unban, user**
    return true to unban user

**make_admin, user**

**remove_admin, user**

Relationship events
===================

**create, <relationship>**
    called after the relationship has been created. Returning false deletes
    the relationship that was just created.

**delete, <relationship>**
    called before the relationship is deleted. Return false to prevent it
    from being deleted.

**join, group**
    user joined a group

**leave, group**
    user left a group

Entity events
=============

**create, <entity type>**
    called for user, group, object, and site entities after creation. Return
    true or entity is deleted.

**update, <entity type>**
    called after group update and return false to delete group. Called after
    object update and return false to delete the object. Called after site
    update and return false to delete site. Called after user update and
    returning false deletes the user. Called before entity update and returning
    false prevents update.

**delete, <entity type>**
    called before entity deletion and returning false prevents deletion.

**disable, <entity type>**
    return false to prevent disable

**enable, <entity type>**
    return false to prevent enable

Metadata events
===============

**create, metadata**
    called after the metadata has been created. Return false to delete the
    metadata that was just created.

**update, metadata**
    called after the metadata has been updated. Return false to delete the
    metadata. (That doesn't sound like a good idea)

**delete, metadata**
    called before metadata is deleted. Return false to prevent deletion.

Annotation events
=================

**annotate, <entity type>**
    called before the annotation has been created. Return false to prevent
    annotation of this entity.

**create, annotation**
    called after the annotation has been created. Return false to delete
    the annotation.

**update, annotation**
    called after the annotation has been updated. Return false to delete the
    annotation. (That doesn't sound like a good idea)

**delete, annotation**
    called before annotation is deleted. Return false to prevent deletion.

Notes
=====

Because of bugs in the Elgg core, some events may be thrown more than once
on the same action. update, object is an example of an event that is thrown twice.