List of events in core
######################

For more information on how events work visit :doc:`/design/events`.

.. contents:: Contents
   :local:
   :depth: 1

.. note::

	Some events are marked with |sequence| this means those events also have a ``:before`` and ``:after`` event
	Also see :ref:`Event sequence <design/events#event-sequence>`

	Some events are marked with |results| this means those events allow altering the output of an event

System events
=============

**activate, plugin**
    Return false to prevent activation of the plugin.
    
**cache:clear, system** |sequence|
    Clear internal and external caches, by default including system_cache, simplecache, and memcache. One might use it to 
    reset others such as APC, OPCache, or WinCache.

**cache:generate, <view>** |results|
	Filters the view output for a ``/cache`` URL when simplecache is disabled. Note this will be fired
	for every ``/cache`` request--no Expires headers are used when simplecache is disabled.

**cache:invalidate, system** |sequence|
    Invalidate internal and external caches.
    
**cache:purge, system** |sequence|
    Purge internal and external caches. This is meant to remove old/stale content from the caches.
    
**commands, cli** |results|
   Allows plugins to register their own commands executable via ``elgg-cli`` binary.
   Handlers must return an array of command class names. Commands must extend ``\Elgg\Cli\Command`` to be executable.
   
**cron, <period>** |results|
	Triggered by cron for each period.

**cron:intervals, system** |results|
	Allow the configuration of custom cron intervals

**deactivate, plugin**
    Return false to prevent deactivation of the plugin.
    
**diagnostics:report, system** |results|
	Filter the output for the diagnostics report download.

**elgg.data, page** |results|
   Filters uncached, page-specific configuration data to pass to the client. :ref:`More info <guides/javascript#config>`
   
**elgg.data, site** |results|
   Filters cached configuration data to pass to the client. :ref:`More info <guides/javascript#config>`
   
**format, friendly:title** |results|
	Formats the "friendly" title for strings. This is used for generating URLs.

**format, friendly:time** |results|
	Formats the "friendly" time for the timestamp ``$params['time']``.

**format, strip_tags** |results|
	Filters a string to remove tags. The original string is passed as ``$params['original_string']``
	and an optional set of allowed tags is passed as ``$params['allowed_tags']``.
	
**gc, system** |results|
	Allows plugins to run garbage collection for ``$params['period']``.

**generate, password** |results|
	Allows plugins to generate new random cleartext passwords. 

**init:cookie, <name>**
    Return false to override setting a cookie.
    
**init, system** |sequence|
    Plugins tend to use this event for initialization (extending views, registering callbacks, etc.)

**languages, translations** |results|
   Allows plugins to add/remove languages from the configurable languages in the system.

**log, systemlog**
	Called for all triggered events by ``system_log`` plugin.
	Used internally by ``Elgg\SystemLog\Logger::log()`` to populate the ``system_log`` table.
	
**login_url, site** |results|
   Filters site's login URL.
   ``$params`` array contains an array of query elements added to the login URL by the invoking script.
   The event must return an absolute URL of the login page.
   
**output:before, page** |results|
    In ``elgg_view_page()``, this filters ``$vars`` before it's passed to the page shell
    view (``page/<page_shell>``). To stop sending the X-Frame-Options header, unregister the
    handler ``Elgg\Page\SetXFrameOptionsHeaderHandler::class`` from this event.

**output, page** |results|
    In ``elgg_view_page()``, this filters the output return value.

**parameters, menu:<menu_name>** |results|
	Triggered by ``elgg_view_menu()``. Used to change menu variables (like sort order) before rendering.

	The ``$params`` array will contain:

	 * ``name`` - name of the menu
	 * ``sort_by`` - preferring sorting parameter
	 * other parameters passed to ``elgg_view_menu()``
	
**plugins_load, system** |sequence|
    Triggered before the plugins are loaded. Rarely used. init, system is used instead. Can be used to load additional libraries.

**plugins_boot, system** |sequence|
    Triggered just after the plugins are loaded. Rarely used. init, system is used instead.
 
**prepare, html** |results|
	Triggered by ``elgg_format_html()`` and used to prepare untrusted HTML.

	The ``$return`` value is an array:

	 * ``html`` - HTML string being prepared
	 * ``options`` - Preparation options

**prepare, menu:<menu_name>** |results|
	Filters the array of menu sections before they're displayed. Each section is a string key mapping to
	an area of menu items. This is a good event to sort, add, remove, and modify menu items. Triggered by
	``elgg_view_menu()`` and ``elgg()->menus->prepareMenu()``.

	The ``$params`` array will contain:

	 * ``selected_item`` - ``ElggMenuItem`` selected in the menu, if any

	The return value is an instance of ``\Elgg\Menu\PreparedMenu``. The prepared menu is a collection of ``\Elgg\Menu\MenuSection``,
	which in turn are collections of ``\ElggMenuItem`` objects.

**prepare, menu:<menu_name>:<type>:<subtype>** |results|
	More granular version of the menu event triggered before the **prepare, menu:<menu_name>** event.
	
	Only applied if menu params contain
	- params['entity'] with an ``\ElggEntity`` (``<type>`` is ``\ElggEntity::type`` and ``<subtype>`` is ``\ElggEntity::subtype``) or
	- params['annotation'] with an ``\ElggAnnotation`` (``<type>`` is ``\ElggAnnotation::getType()`` and ``<subtype>`` is ``\ElggAnnotation::getSubtype()``) or
	- params['relationship'] with an ``\ElggRelationship`` (``<type>`` is ``\ElggRelationship::getType()`` and ``<subtype>`` is ``\ElggRelationship::getSubtype()``)

**ready, system** |sequence|
	Triggered after the ``init, system`` event. All plugins are fully loaded and the engine is ready
	to serve pages.

**regenerate_site_secret:before, system**
    Return false to cancel regenerating the site secret. You should also provide a message
    to the user.

**regenerate_site_secret:after, system**
    Triggered after the site secret has been regenerated.
     
**register, menu:<menu_name>** |results|
	Filters the initial list of menu items pulled from configuration, before the menu has been split into
	sections. Triggered by ``elgg_view_menu()`` and ``elgg()->menus->getMenu()``.

	The ``$params`` array will contain parameters returned by ``parameters, menu:<menu_name>`` event.

	The return value is an instance of ``\Elgg\Menu\MenuItems`` containing ``\ElggMenuItem`` objects.

	Event handlers can add/remove items to the collection using the collection API, as well as array access operations.

**register, menu:<menu_name>:<type>:<subtype>** |results|
	More granular version of the menu event triggered before the **register, menu:<menu_name>** event.
	
	Only applied if menu params contain
	- params['entity'] with an ``\ElggEntity`` (``<type>`` is ``\ElggEntity::type`` and ``<subtype>`` is ``\ElggEntity::subtype``) or
	- params['annotation'] with an ``\ElggAnnotation`` (``<type>`` is ``\ElggAnnotation::getType()`` and ``<subtype>`` is ``\ElggAnnotation::getSubtype()``) or
	- params['relationship'] with an ``\ElggRelationship`` (``<type>`` is ``\ElggRelationship::getType()`` and ``<subtype>`` is ``\ElggRelationship::getSubtype()``)

**register, menu:filter:<filter_id>** |results|
	Allows plugins to modify layout filter tabs on layouts that specify ``<filter_id>`` parameter. Parameters and return values
	are same as in ``register, menu:<menu_name>`` event.
	
	If ``filter_id`` is ``filter`` (the default) then the ``all``, ``mine`` and ``friends`` tabs will be generated base on some provided information
	or be tried for routes similar to the current route.
	
	- params['all_link'] will be used for the ``all`` tab
	- params['mine_link'] will be used for the ``mine`` tab
	- params['friends_link'] will be used for the ``friend`` tab
	
	If the above are not provided than a route will be tried based on ``params['entity_type']`` and ``params['entity_subtype']``.
	If not provided ``entity_type`` and ``entity_subtype`` will be based on route detection of the current route. 
	For example if the current route is ``collection:object:blog:all`` ``entity_type`` will be ``object`` and ``entity_subtype`` will be ``blog``.
	- The ``all`` tab will be based on the route ``collection:<entity_type>:<entity_subtype>:all``
	- The ``mine`` tab will be based on the route ``collection:<entity_type>:<entity_subtype>:owner``
	- The ``friend`` tab will be based on the route ``collection:<entity_type>:<entity_subtype>:friends``

	If the routes aren't registered the tabs will not appear.
	
**registration_url, site** |results|
   Filters site's registration URL. Can be used by plugins to attach invitation codes, referrer codes etc. to the registration URL.
   ``$params`` array contains an array of query elements added to the registration URL by the invoking script.
   The event must return an absolute URL to the registration page.

**reload:after, translations**
    Triggered after the translations are (re)loaded.
    
**sanitize, input** |results|
	Filter GET and POST input. This is used by ``get_input()`` to sanitize user input.

**seeds, database** |results|
   Allows plugins to register their own database seeds. Seeds populate the database with fake entities for testing purposes.
   Seeds must extend ``\Elgg\Database\Seeds\Seed`` class to be executable via ``elgg-cli database:seed``.

**send:before, http_response**
    Triggered before an HTTP response is sent. Handlers will receive an instance of `\Symfony\Component\HttpFoundation\Response` 
    that is to be sent to the requester. Handlers can terminate the event and prevent the response from being sent by returning `false`.

**send:after, http_response**
    Triggered after an HTTP response is sent. Handlers will receive an instance of `\Symfony\Component\HttpFoundation\Response` 
    that was sent to the requester.
    
**shutdown, system**
    Triggered after the page has been sent to the user. Expensive operations could be done here
    and not make the user wait.

.. note:: Depending upon your server configuration the PHP output
    might not be shown until after the process is completed. This means that any long-running
    processes will still delay the page load.

.. note:: This event is prefered above using ``register_shutdown_function`` as you may not have access
    to all the Elgg services (eg. database) in the shutdown function but you will in the event.

.. note:: The Elgg session is already closed before this event. Manipulating session is not possible.

**simplecache:generate, <view>** |results|
	Filters the view output for a ``/cache`` URL when simplecache is enabled.

**upgrade, system**
	Triggered after a system upgrade has finished. All upgrade scripts have run, but the caches 
	are not cleared.

**upgrade:execute, system** |sequence|
	Triggered when executing an ``ElggUpgrade``. The ``$object`` of the event is the ``ElggUpgrade``.

User events
===========

**ban, user**
    Triggered before a user is banned. Return false to prevent.

**change:email, user** |results|
	Triggered before the user email is changed.
	Allows plugins to implement additional logic required to change email, e.g. additional email validation.
	The event handler must return false to prevent the email from being changed right away.

	The ``$params`` array will contain:

	 * ``user`` - ``\ElggUser``, whose settings are being saved
	 * ``email`` - Email address that passes sanity checks
	 * ``request`` - ``\Elgg\Request`` to the action controller
	 
**invalidate:after, user**
    Triggered when user's account validation has been revoked.
    
**login:after, user**
	Triggered after the user logs in.

**login:before, user**
    Triggered during login. Returning false prevents the user from logging
    
**login:forward, user** |results|
    Filters the URL to which the user will be forwarded after login.
    
**login:first, user**
    Triggered after a successful login. Only if there is no previous login.

**logout:after, user**
	Triggered after the user logouts.
	
**logout:before, user**
    Triggered during logout. Returning false should prevent the user from logging out.

**make_admin, user**
	Triggered before a user is promoted to an admin. Return false to prevent.
	
**profileiconupdate, user**
    User has changed profile icon
    
**profileupdate, user**
    User has changed profile

**register, user** |results|
	Triggered by the ``register`` action after the user registers. Return ``false`` to delete the user.
	Note the function ``register_user`` does *not* trigger this event.
	Event handlers can throw ``\Elgg\Exceptions\Configuration\RegistrationException`` with an error message to be displayed to the user.

	The ``$params`` array will contain:

	 * ``user`` - Newly registered user entity
	 * All parameters sent with the request to the action (incl. ``password``, ``friend_guid``, ``invitecode`` etc)

**registeruser:validate:email, all** |results|
	Return boolean for if the string in ``$params['email']`` is valid for an email address.
	Event handler can throw ``\Elgg\Exceptions\Configuration\RegistrationException`` with an error message to be shown to the user.

**registeruser:validate:password, all** |results|
	Return boolean for if the string in ``$params['password']`` is valid for a password.
	Event handler can throw ``\Elgg\Exceptions\Configuration\RegistrationException`` with an error message to be shown to the user.

**registeruser:validate:username, all** |results|
	Return boolean for if the string in ``$params['username']`` is valid for a username.
	Event handler can throw ``\Elgg\Exceptions\Configuration\RegistrationException`` with an error message to be shown to the user.

**remove_admin, user**
	Triggered before a user is demoted from an admin. Return false to prevent.
	
**unban, user**
    Triggered before a user is unbanned. Return false to prevent.

**username:character_blacklist, user** |results|
	Filters the string of blacklisted characters used to validate username during registration.
	The return value should be a string consisting of the disallowed characters. The default
	string can be found from ``$params['blacklist']``.
	
**usersettings:save, user** |results|
	Triggered in the aggregate action to save user settings.
	The event handler must return ``false`` to prevent sticky forms from being cleared (i.e. to indicate that some of the values were not saved).
	Do not return ``true`` from your event handler, as you will override other events' output, instead return ``null`` to indicate successful operation.

	The ``$params`` array will contain:

	 * ``user`` - ``\ElggUser``, whose settings are being saved
	 * ``request`` - ``\Elgg\Request`` to the action controller
	 
**validate, user**
    When a user registers, the user's account is disabled. This event is triggered
    to allow a plugin to determine how the user should be validated (for example,
    through an email with a validation link).

**validate:after, user**
    Triggered when user's account has been validated.

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
	
**comments, <entity_type>** |results|
	Triggered in ``elgg_view_comments()``. If returning content, this overrides the
	``page/elements/comments`` view.

**comments:count, <entity_type>** |results|
	Return the number of comments on ``$params['entity']``.

**create, <entity type>**
    Triggered for user, group, object, and site entities after creation. Triggered just before the ``create:after`` event,
    mostly for BC reasons. The use of the ``create:after`` event is preferred.

**create:after, <entity type>**
    Triggered for user, group, object, and site entities after creation.

**create:before, <entity type>**
    Triggered for user, group, object, and site entities before creation. Return false to prevent creating the entity.

**delete, <entity type>**
    Triggered before entity deletion.

**delete:after, <entity type>**
    Triggered after entity deletion.

**delete:before, <entity type>**
    Triggered before entity deletion. Return false to prevent deletion.

**disable, <entity type>**
    Triggered before the entity is disabled. Return false to prevent disabling.

**disable:after, <entity type>**
	Triggered after the entity is disabled.

**enable, <entity type>**
    Return false to prevent enabling.

**enable:after, <entity type>**
	Triggered after the entity is enabled.

**likes:count, <entity_type>** |results|
	Return the number of likes for ``$params['entity']``.
	
**update, <entity type>**
    Triggered before an update for the user, group, object, and site entities. Return false to prevent update.
    The entity method ``getOriginalAttributes()`` can be used to identify which attributes have changed since
    the entity was last saved.

**update:after, <entity type>**
    Triggered after an update for the user, group, object, and site entities.
    The entity method ``getOriginalAttributes()`` can be used to identify which attributes have changed since
    the entity was last saved.

Metadata events
===============

**create, metadata**
    Called after the metadata has been created. Return false to delete the
    metadata that was just created.

**delete, metadata**
    Called before metadata is deleted. Return false to prevent deletion.
    
**update, metadata**
    Called after the metadata has been updated. Return false to *delete the metadata.*

Annotation events
=================

**annotate, <entity type>**
    Called before the annotation has been created. Return false to prevent
    annotation of this entity.

**create, annotation**
    Called after the annotation has been created. Return false to delete
    the annotation.

**delete, annotation**
    Called before annotation is deleted. Return false to prevent deletion.

**disable, annotations**
	Called when disabling annotations. Return false to prevent disabling.
	
**enable, annotation**
	Called when enabling annotations. Return false to prevent enabling.
	
**update, annotation**
    Called after the annotation has been updated. Return false to *delete the annotation.*

River events
============

**create:after, river**
	Called after a river item is created.
	
**create:before, river**
	Called before the river item is saved to the database. Return ``false`` to prevent the item from being created. 

**delete:after, river**
	Triggered after a river item was deleted.

**delete:before, river**
	Triggered before a river item is deleted. Returning false cancels the deletion.
	
.. _guides/events-list#access-events:

Access events
=============

**access_collection:url, access_collection** |results|
	Can be used to filter the URL of the access collection.

	The ``$params`` array will contain:

	 * ``access_collection`` - `ElggAccessCollection`

**access_collection:name, access_collection** |results|
	Can be used to filter the display name (readable access level) of the access collection.

	The ``$params`` array will contain:

	 * ``access_collection`` - `ElggAccessCollection`

**access:collections:read, user** |results|
	Filters an array of access IDs that the user ``$params['user_id']`` can see.

	.. warning:: 
		The handler needs to either not use parts of the API that use the access system (triggering the event again) or 
		to ignore the second call. Otherwise, an infinite loop will be created.

**access:collections:write, user** |results|
	Filters an array of access IDs that the user ``$params['user_id']`` can write to. In
	``elgg_get_write_access_array()``, this event filters the return value, so it can be used to alter
	the available options in the ``input/access`` view. For core plugins, the value "input_params"
	has the keys "entity" (ElggEntity|false), "entity_type" (string), "entity_subtype" (string),
	"container_guid" (int) are provided. An empty entity value generally means the form is to
	create a new object.

	.. warning:: 
		The handler needs to either not use parts of the API that use the access system (triggering the event again) or 
		to ignore the second call. Otherwise, an infinite loop will be created.

**access:collections:write:subtypes, user** |results|
	Returns an array of access collection subtypes to be used when retrieving access collections owned by a user as part of 
	the ``elgg_get_write_access_array()`` function.
	
**access:collections:add_user, collection** |results|
	Triggered before adding user ``$params['user_id']`` to collection ``$params['collection_id']``.
	Return false to prevent adding.

**access:collections:remove_user, collection** |results|
	Triggered before removing user ``$params['user_id']`` to collection ``$params['collection_id']``.
	Return false to prevent removal.

**create, access_collection** |sequence|
	Triggered during the creation of an ``ElggAccessCollection``.

**delete, access_collection** |sequence|
	Triggered during the deletion of an ``ElggAccessCollection``.

**get_sql, access** |results|
	Filters SQL clauses restricting/allowing access to entities and annotations.

	.. note::
		**The event is triggered regardless if the access is ignored**. 
		The handlers may need to check if access is ignored and return early, if appended clauses should only apply to 
		access controlled contexts.

	``$return`` value is a nested array of ``ands`` and ``ors``.

	``$params`` includes:

	 * ``table_alias`` - alias of the main table used in select clause
	 * ``ignore_access`` - whether ignored access is enabled
	 * ``use_enabled_clause`` - whether disabled entities are shown/hidden
	 * ``access_column`` - column in the main table containing the access collection ID value
	 * ``owner_guid_column`` - column in the main table referencing the GUID of the owner
	 * ``guid_column`` - column in the main table referencing the GUID of the entity
	 * ``enabled_column`` - column in the main table referencing the enabled status of the entity
	 * ``query_builder`` - an instance of the ``QueryBuilder``

**update, access_collection** |sequence|
	Triggered during the update of an ``ElggAccessCollection``.
	 
.. _guides/events-list#permissions:

Permission events
=================

**container_logic_check, <entity_type>** |results|
	Triggered by ``ElggEntity:canWriteToContainer()`` before triggering ``permissions_check`` and ``container_permissions_check``
	events. Unlike permissions events, logic check can be used to prevent certain entity types from being contained
	by other entity types, e.g. discussion replies should only be contained by discussions. This event can also be
	used to apply status logic, e.g. do disallow new replies for closed discussions.

	The handler should return ``false`` to prevent an entity from containing another entity. The default value passed to the event
	is ``null``, so the handler can check if another event has modified the value by checking if return value is set.
	Should this event return ``false``, ``container_permissions_check`` and ``permissions_check`` events will not be triggered.

	The ``$params`` array will contain:

	 * ``container`` - An entity that will be used as a container
	 * ``user`` - User who will own the entity to be written to container
	 * ``subtype`` - Subtype of the entity to be written to container (entity type is assumed from event type)

**container_permissions_check, <entity_type>** |results|
	Return boolean for if the user ``$params['user']`` can use the entity ``$params['container']``
	as a container for an entity of ``<entity_type>`` and subtype ``$params['subtype']``.

	In the rare case where an entity is created with neither the ``container_guid`` nor the ``owner_guid``
	matching the logged in user, this event is called *twice*, and in the first call ``$params['container']``
	will be the *owner*, not the entity's real container.

	The ``$params`` array will contain:

	 * ``container`` - An entity that will be used as a container
	 * ``user`` - User who will own the entity to be written to container
	 * ``subtype`` - Subtype of the entity to be written to container (entity type is assumed from event type)

**permissions_check, <entity_type>** |results|
	Return boolean for if the user ``$params['user']`` can edit the entity ``$params['entity']``.

**permissions_check:delete, <entity_type>** |results|
	Return boolean for if the user ``$params['user']`` can delete the entity ``$params['entity']``. Defaults to ``$entity->canEdit()``.

**permissions_check:delete, river** |results|
	Return boolean for if the user ``$params['user']`` can delete the river item ``$params['item']``. Defaults to
	``true`` for admins and ``false`` for other users.

**permissions_check:download, file** |results|
	Return boolean for if the user ``$params['user']`` can download the file in ``$params['entity']``.

	The ``$params`` array will contain:

	 * ``entity`` - Instance of ``ElggFile``
	 * ``user`` - User who will download the file

**permissions_check, widget_layout** |results|
	Return boolean for if ``$params['user']`` can edit the widgets in the context passed as
	``$params['context']`` and with a page owner of ``$params['page_owner']``.

**permissions_check:comment, <entity_type>** |results|
	Return boolean for if the user ``$params['user']`` can comment on the entity ``$params['entity']``.

**permissions_check:annotate:<annotation_name>, <entity_type>** |results|
	Return boolean for if the user ``$params['user']`` can create an annotation ``<annotation_name>`` on the
	entity ``$params['entity']``. If logged in, the default is true.

	.. note:: This is called before the more general ``permissions_check:annotate`` event, and its return value is that event's initial value.

**permissions_check:annotate, <entity_type>** |results|
	Return boolean for if the user ``$params['user']`` can create an annotation ``$params['annotation_name']``
	on the entity ``$params['entity']``. if logged in, the default is true.

**api_key, use** |results|
	Triggered in the class ``\Elgg\WebServices\PAM\API\APIKey``. Returning false prevents the key from being authenticated.

**gatekeeper, <entity_type>:<entity_subtype>** |results|
    Filters the result of ``elgg_entity_gatekeeper()`` to prevent or allow access to an entity that user would otherwise have or not have access to.
    A handler can return ``false`` or an instance of ``\Elgg\Exceptions\HttpException`` to prevent access to an entity.
    A handler can return ``true`` to override the result of the gatekeeper.
    **Important** that the entity received by this event is fetched with ignored access and including disabled entities,
    so you have to be careful to not bypass the access system.

    ``$params`` array includes:

	 * ``entity`` - Entity that is being accessed
	 * ``user`` - User accessing the entity (``null`` implies logged in user)

Notifications events
====================

**dequeue, notifications**
	Called when an ElggData object is removed from the notifications queue to be processed 

**enqueue, notifications**
	Called when an ElggData object is being added to the notifications queue 
	
The following events are listed chronologically in the lifetime of the notification event.
Note that not all events apply to instant notifications.

**enqueue, notification** |results|
	Can be used to prevent a notification event from sending **subscription** notifications.
	Event handler must return ``false`` to prevent a subscription notification event from being enqueued.

	``$params`` array includes:

	 * ``object`` - object of the notification event
	 * ``action`` - action that triggered the notification event. E.g. corresponds to ``publish`` when ``elgg_trigger_event('publish', 'object', $object)`` is called

**get, subscriptions** |results|
	Filters subscribers of the notification event.
	Applies to **subscriptions** and **instant** notifications.
	In case of a subscription event, by default, the subscribers list consists of the users subscribed to the container entity of the event object.
	In case of an instant notification event, the subscribers list consists of the users passed as recipients to ``notify_user()``

   **IMPORTANT** Always validate the notification event, object and/or action types before adding any new recipients to ensure that you do not accidentally dispatch notifications to unintended recipients.
   Consider a situation, where a mentions plugin sends out an instant notification to a mentioned user - any event acting on a subject or an object without validating an event or action type (e.g. including an owner of the original wire thread) might end up sending notifications to wrong users.

	``$params`` array includes:

	 * ``event`` - ``\Elgg\Notifications\NotificationEvent`` instance that describes the notification event
	 * ``origin`` - ``subscriptions_service`` or ``instant_notifications``
	 * ``methods_override`` - delivery method preference for instant notifications

	Handlers must return an array in the form:

.. code-block:: php

	array(
		<user guid> => array('sms'),
		<user_guid2> => array('email', 'sms', 'ajax')
	);


**send:before, notifications** |results|
	Triggered before the notification event queue is processed. Can be used to terminate the notification event.
	Applies to **subscriptions** and **instant** notifications.

	``$params`` array includes:

	 * ``event`` - ``\Elgg\Notifications\NotificationEvent`` instance that describes the notification event
	 * ``subscriptions`` - a list of subscriptions. See ``'get', 'subscriptions'`` event for details

**prepare, notification** |results|
	A high level event that can be used to alter an instance of ``\Elgg\Notifications\Notification`` before it is sent to the user.
	Applies to **subscriptions** and **instant** notifications.
	This event is triggered before a more granular ``'prepare', 'notification:<action>:<entity_type>:<entity_subtype>'`` and after ``'send:before', 'notifications``.
	Event handler should return an altered notification object.

	``$params`` may vary based on the notification type and may include:

	 * ``event`` - ``\Elgg\Notifications\NotificationEvent`` instance that describes the notification event
	 * ``object`` - object of the notification ``event``. Can be ``null`` for instant notifications
	 * ``action`` - action that triggered the notification ``event``. May default to ``notify_user`` for instant notifications
	 * ``method`` - delivery method (e.g. ``email``, ``site``)
	 * ``sender`` - sender
	 * ``recipient`` - recipient
	 * ``language`` - language of the notification (recipient's language)
	 * ``origin`` - ``subscriptions_service`` or ``instant_notifications``

**prepare, notification:<action>:<entity_type>:<entity_type>** |results|
	A granular event that can be used to filter a notification ``\Elgg\Notifications\Notification`` before it is sent to the user.
	Applies to **subscriptions** and **instant** notifications.
	In case of instant notifications that have not received an object, the event will be called as ``'prepare', 'notification:<action>'``.
	In case of instant notifications that have not received an action name, it will default to ``notify_user``.

	``$params`` include:

	 * ``event`` - ``\Elgg\Notifications\NotificationEvent`` instance that describes the notification event
	 * ``object`` - object of the notification ``event``. Can be ``null`` for instant notifications
	 * ``action`` - action that triggered the notification ``event``. May default to ``notify_user`` for instant notifications
	 * ``method`` - delivery method (e.g. ``email``, ``site``)
	 * ``sender`` - sender
	 * ``recipient`` - recipient
	 * ``language`` - language of the notification (recipient's language)
	 * ``origin`` - ``subscriptions_service`` or ``instant_notifications``

**format, notification:<method>** |results|
	This event can be used to format a notification before it is passed to the ``'send', 'notification:<method>'`` event.
	Applies to **subscriptions** and **instant** notifications.
	The event handler should return an instance of ``\Elgg\Notifications\Notification``.
	The event does not receive any ``$params``.
	Some of the use cases include:

	 * Strip tags from notification title and body for plaintext email notifications
	 * Inline HTML styles for HTML email notifications
	 * Wrap notification in a template, add signature etc.

**send, notification:<method>** |results|
	Delivers a notification.
	Applies to **subscriptions** and **instant** notifications.
	The handler must return ``true`` or ``false`` indicating the success of the delivery.

	``$params`` array includes:

	 * ``notification`` - a notification object ``\Elgg\Notifications\Notification``

**send:after, notifications** |results|
	Triggered after all notifications in the queue for the notifications event have been processed.
	Applies to **subscriptions** and **instant** notifications.

	``$params`` array includes:

	 * ``event`` - ``\Elgg\Notifications\NotificationEvent`` instance that describes the notification event
	 * ``subscriptions`` - a list of subscriptions. See ``'get', 'subscriptions'`` event for details
	 * ``deliveries`` - a matrix of delivery statuses by user for each delivery method

Emails
======

**prepare, system:email** |results|
	Triggered by ``elgg_send_email()``.
	Applies to all outgoing system and notification emails.
	This event allows you to alter an instance of ``\Elgg\Email`` before it is passed to the email transport.
	This event can be used to alter the sender, recipient, subject, body, and/or headers of the email.

	``$params`` are empty. The ``$return`` value is an instance of ``\Elgg\Email``.

**transport, system:email** |results|
	Triggered by ``elgg_send_email()``.
	Applies to all outgoing system and notification emails.
	This event allows you to implement a custom email transport, e.g. delivering emails via a third-party proxy service such as SendGrid or Mailgun.
	The handler must return ``true`` to indicate that the email was transported.

	``$params`` contains:

	 * ``email`` - An instance of ``\Elgg\Email``
	 
**validate, system:email** |results|
	Triggered by ``elgg_send_email()``.
	Applies to all outgoing system and notification emails.
	This event allows you to suppress or whitelist outgoing emails, e.g. when the site is in a development mode.
	The handler must return ``false`` to supress the email delivery.

	``$params`` contains:

	 * ``email`` - An instance of ``\Elgg\Email``

**zend:message, system:email** |results|
	Triggered by the default email transport handler (Elgg uses ``laminas/laminas-mail``).
	Applies to all outgoing system and notification emails that were not transported using the **transport, system:email** event.
	This event allows you to alter an instance of ``\Laminas\Mail\Message`` before it is passed to the Laminas email transport.

	``$params`` contains:

	 * ``email`` - An instance of ``\Elgg\Email``

File events
===========

**download:url, file** |results|
    Allows plugins to filter the download URL of the file.
	By default, the download URL is generated by the file service.

    ``$params`` array includes:

     * ``entity`` - instance of ``ElggFile``
     * ``use_cookie`` - whether or not to use a cookie to secure download link
     * ``expires`` - a string representation of when the download link should expire

**inline:url, file** |results|
    Allows plugins to filter the inline URL of the image file.
	By default, the inline URL is generated by the file service.

    ``$params`` array includes:

     * ``entity`` - instance of ``ElggFile``
     * ``use_cookie`` - whether or not to use a cookie to secure download link
     * ``expires`` - a string representation of when the download link should expire

**mime_type, file** |results|
	Return the mimetype for the filename ``$params['filename']`` with original filename ``$params['original_filename']``
	and with the default detected mimetype of ``$params['default']``.

**simple_type, file** |results|
    The event provides ``$params['mime_type']`` (e.g. ``application/pdf`` or ``image/jpeg``) and determines an overall 
    category like ``document`` or ``image``. The bundled file plugin and other-third party plugins usually store
    ``simpletype`` metadata on file entities and make use of it when serving icons and constructing
    ``ege*`` filters and menus.

**upload, file** |results|
    Allows plugins to implement custom logic for moving an uploaded file into an instance of ``ElggFile``.
    The handler must return ``true`` to indicate that the uploaded file was moved.
    The handler must return ``false`` to indicate that the uploaded file could not be moved.
    Other returns will indicate that ``ElggFile::acceptUploadedFile`` should proceed with the
    default upload logic.

    ``$params`` array includes:

     * ``file`` - instance of ``ElggFile`` to write to
     * ``upload`` - instance of Symfony's ``UploadedFile``

**upload:after, file**
    Called after an uploaded file has been written to filestore. Receives an
    instance of ``ElggFile`` the uploaded file was written to. The ``ElggFile``
    may or may not be an entity with a GUID.
    
Action events
=============

**action:validate, <action>** |results|
	Trigger before action script/controller is executed.
	This event should be used to validate/alter user input, before proceeding with the action.
	The event handler can throw an instance of ``\Elgg\Exceptions\Http\ValidationException`` or return ``false``
	to terminate further execution.

    ``$params`` array includes:

     * ``request`` - instance of ``\Elgg\Request``

**action_gatekeeper:permissions:check, all** |results|
	Triggered after a CSRF token is validated. Return false to prevent validation.

**forward, <reason>** |results|
	Filter the URL to forward a user to when ``forward($url, $reason)`` is called.
	In certain cases, the ``params`` array will contain an instance of ``\Elgg\Exceptions\HttpException`` that triggered the error.

**response, action:<action>** |results|
    Filter an instance of ``\Elgg\Http\ResponseBuilder`` before it is sent to the client.
    This event can be used to modify response content, status code, forward URL, or set additional response headers.
    Note that the ``<action>`` value is parsed from the request URL, therefore you may not be able to filter
    the responses of `action()` calls if they are nested within the another action script file.

.. _guides/events-list#ajax:

Ajax
====

**ajax_response, \*** |results|
	When the ``elgg/Ajax`` AMD module is used, this event gives access to the response object
	(``\Elgg\Services\AjaxResponse``) so it can be altered/extended. The event type depends on
	the method call:

	================  ====================
	elgg/Ajax method  event type
	================  ====================
	action()          action:<action_name>
	path()            path:<url_path>
	view()            view:<view_name>
	form()            form:<action_name>
	================  ====================

**ajax_response, action:<action_name>** |results|
    Filters ``action/`` responses before they're sent back to the ``elgg/Ajax`` module.
    
**ajax_response, path:<path>** |results|
    Filters ajax responses before they're sent back to the ``elgg/Ajax`` module. This event type will
    only be used if the path did not start with "action/" or "ajax/".
    
**ajax_response, view:<view>** |results|
    Filters ``ajax/view/`` responses before they're sent back to the ``elgg/Ajax`` module.

**ajax_response, form:<action_name>** |results|
    Filters ``ajax/form/`` responses before they're sent back to the ``elgg/Ajax`` module.

Routing
=======

**response, path:<path>** |results|
    Filter an instance of ``\Elgg\Http\ResponseBuilder`` before it is sent to the client.
    This event type will only be used if the path did not start with "action/" or "ajax/".
    This event can be used to modify response content, status code, forward URL, or set additional response headers.
    Note that the ``<path>`` value is parsed from the request URL, therefore plugins using the ``route`` event should
    use the original ``<path>`` to filter the response, or switch to using the ``route:rewrite`` event.

**route:config, <route_name>** |results|
	Allows altering the route configuration before it is registered.
	This event can be used to alter the path, default values, requirements, as well as to set/remove middleware.
	Please note that the handler for this event should be registered outside of the ``init`` event handler, as core routes are registered during ``plugins_boot`` event.

**route:rewrite, <identifier>** |results|
	Allows altering the site-relative URL path for an incoming request. See :doc:`routing` for details.
	Please note that the handler for this event should be registered outside of the ``init`` event handler, as route rewrites take place after ``plugins_boot`` event has completed.

.. _guides/events-list#views:

Views
=====

**attributes, htmlawed** |results|
	Allows changes to individual attributes.

**allowed_styles, htmlawed** |results|
	Configure allowed styles for HTMLawed.

**config, htmlawed** |results|
	Filter the HTMLawed ``$config`` array.

**form:prepare:fields, <form_name>** |results|
	Prepare field values for use in the form. Eg. when editing a blog, fill this with the current values of the blog.
	Sticky form values will automatically be added to the field values (when available).

**head, page** |results|
    In ``elgg_view_page()``, filters ``$vars['head']``
    Return value contains an array with ``title``, ``metas`` and ``links`` keys,
    where ``metas`` is an array of elements to be formatted as ``<meta>`` head tags,
    and ``links`` is an array of elements to be formatted as ``<link>`` head tags.
    Each meta and link element contains a set of key/value pairs that are formatted
    into html tag attributes, e.g.

.. code-block:: php

    return [
       'title' => 'Current page title',
       'metas' => [
          'viewport' => [
             'name' => 'viewport',
             'content' => 'width=device-width',
          ]
       ],
       'links' => [
          'rss' => [
             'rel' => 'alternative',
             'type' => 'application/rss+xml',
             'title' => 'RSS',
             'href' => elgg_format_url($url),
          ],
          'icon-16' => [
             'rel' => 'icon',
             'sizes' => '16x16',
             'type' => 'image/png',
             'href' => elgg_get_simplecache_url('graphics/favicon-16.png'),
          ],
       ],
    ];

**layout, page** |results|
    In ``elgg_view_layout()``, filters the layout name.
    ``$params`` array includes:

     * ``identifier`` - ID of the page being rendered
     * ``segments`` - URL segments of the page being rendered
     * other ``$vars`` received by ``elgg_view_layout()``

**response, form:<form_name>** |results|
    Filter an instance of ``\Elgg\Http\ResponseBuilder`` before it is sent to the client.
    Applies to request to ``/ajax/form/<form_name>``.
    This event can be used to modify response content, status code, forward URL, or set additional response headers.
    
**response, view:<view_name>** |results|
    Filter an instance of ``\Elgg\Http\ResponseBuilder`` before it is sent to the client.
    Applies to request to ``/ajax/view/<view_name>``.
    This event can be used to modify response content, status code, forward URL, or set additional response headers.
    
**shell, page** |results|
    In ``elgg_view_page()``, filters the page shell name

**spec, htmlawed** |results|
	Filter the HTMLawed ``$spec`` string (default empty).
	
**table_columns:call, <name>** |results|
    When the method ``elgg()->table_columns->$name()`` is called, this event is called to allow
    plugins to override or provide an implementation. Handlers receive the method arguments via
    ``$params['arguments']`` and should return an instance of ``Elgg\Views\TableColumn`` if they
    wish to specify the column directly.
    
**vars:compiler, css** |results|
    Allows plugins to alter CSS variables passed to CssCrush during compilation.
    See `CSS variables <_guides/theming#css-vars>`.
    
**view, <view_name>** |results|
    Filters the returned content of the view
    
**view_vars, <view_name>** |results|
	Filters the ``$vars`` array passed to the view

.. _guides/events-list#search:

Search
======

**search:config, search_types** |results|
    Implemented in the **search** plugin.
    Filters an array of custom search types. This allows plugins to add custom search types (e.g. tag or location search).
    Adding a custom search type will extend the search plugin user interface with appropriate links and lists.

**search:config, type_subtype_pairs** |results|
    Implemented in the **search** plugin.
    Filters entity type/subtype pairs before entity search is performed.
    Allows plugins to remove certain entity types/subtypes from search results, group multiple subtypes together, or to reorder search sections.

**search:fields, <entity_type>** |results|
    Triggered by ``elgg_search()``. Filters search fields before search clauses are prepared.
    ``$return`` value contains an array of names for each entity property type, which should be matched against the search query.
    ``$params`` array contains an array of search params passed to and filtered by ``elgg_search()``.

.. code-block:: php

    return [
        'attributes' => [],
        'metadata' => ['title', 'description'],
        'annotations' => ['revision'],
    ];

**search:fields, <entity_type>:<entity_subtype>** |results|
   See **search:fields, <entity_type>**

**search:fields, <search_type>** |results|
    See **search:fields, <entity_type>**

**search:format, entity** |results|
    Implemented in the **search** plugin.
    Allows plugins to populate entity's volatile data before it's passed to search view.
    This is used for highlighting search hit, extracting relevant substrings in long text fields etc.

**search:options, <entity_type>** |results|
    Triggered by ``elgg_search()``. Prepares search clauses (options) to be passed to ``elgg_get_entities()``.

**search:options, <entity_type>:<entity_subtype>** |results|
    See **search:options, <entity_type>**

**search:options, <search_type>** |results|
    See **search:options, <entity_type>**

**search:params, <search_type>** |results|
    Triggered by ``elgg_search()``. Filters search parameters (query, sorting, search fields etc) before search clauses are prepared for a given search type.
    Elgg core only provides support for ``entities`` search type.
    
**search:results, <search_type>** |results|
    Triggered by ``elgg_search()``. Receives normalized options suitable for ``elgg_get_entities()`` call and must return an array of entities matching search options.
    This event is designed for use by plugins integrating third-party indexing services, such as Solr and Elasticsearch.

.. _guides/events-list#other:

Other
=====

**config, comments_per_page** |results|
	Filters the number of comments displayed per page. Default is 25. ``$params['entity']`` will hold
	the containing entity or null if not provided. Use ``elgg_comments_per_page()`` to get the value.

**config, comments_latest_first** |results|
	Filters the order of comments. Default is ``true`` for latest first. ``$params['entity']`` will hold
	the containing entity or null if not provided.

**default, access** |results|
	In ``elgg_get_default_access()``, this event filters the return value, so it can be used to alter
	the default value in the input/access view. For core plugins, the value "input_params" has
	the keys "entity" (ElggEntity|false), "entity_type" (string), "entity_subtype" (string),
	"container_guid" (int) are provided. An empty entity value generally means the form is to
	create a new object.

**classes, icon** |results|
	Can be used to filter CSS classes applied to icon glyphs. By default, Elgg uses FontAwesome. Plugins can use this
	event to switch to a different font family and remap icon classes.

**config, amd** |results|
	Filter the AMD config for the requirejs library.
	
**entity:icon:sizes, <entity_type>** |results|
	Triggered by ``elgg_get_icon_sizes()`` and sets entity type/subtype specific icon sizes.
	``entity_subtype`` will be passed with the ``$params`` array to the callback.

**entity:<icon_type>:sizes, <entity_type>** |results|
	Allows filtering sizes for custom icon types, see ``entity:icon:sizes, <entity_type>``.

	The event must return an associative array where keys are the names of the icon sizes
	(e.g. "large"), and the values are arrays with the following keys:

     * ``w`` - Width of the image in pixels
     * ``h`` - Height of the image in pixels
     * ``square`` - Should the aspect ratio be a square (true/false)
     * ``upscale`` - Should the image be upscaled in case it is smaller than the given width and height (true/false)
     * ``crop`` - Is cropping allowed on this image size (true/false, default: true)

	If the configuration array for an image size is empty, the image will be
	saved as an exact copy of the source without resizing or cropping.

	Example:

.. code-block:: php

	return [
		'small' => [
			'w' => 60,
			'h' => 60,
			'square' => true,
			'upscale' => true,
		],
		'large' => [
			'w' => 600,
			'h' => 600,
			'upscale' => false,
		],
		'original' => [],
	];

**entity:icon:url, <entity_type>** |results|
	Triggered when entity icon URL is requested, see :ref:`entity icons <guides/database#entity-icons>`. Callback should
	return URL for the icon of size ``$params['size']`` for the entity ``$params['entity']``.
	Following parameters are available through the ``$params`` array:

	entity
		Entity for which icon url is requested.
	viewtype
		The type of :ref:`view <guides/views#listing-entities>` e.g. ``'default'`` or ``'json'``.
	size
		Size requested, see :ref:`entity icons <guides/database#entity-icons>` for possible values.

	Example on how one could default to a Gravatar icon for users that
	have not yet uploaded an avatar:

.. code-block:: php

	// Priority 600 so that handler is triggered after avatar handler
	elgg_register_event_handler('entity:icon:url', 'user', 'gravatar_icon_handler', 600);

	/**
	 * Default to icon from gravatar for users without avatar.
	 *
	 * @param \Elgg\Event $event 'entity:icon:url', 'user'
	 *
	 * @return string
	 */
	function gravatar_icon_handler(\Elgg\Event $event) {
		$entity = $event->getEntityParam();
		
		// Allow users to upload avatars
		if ($entity->icontime) {
			return $url;
		}

		// Generate gravatar hash for user email
		$hash = md5(strtolower(trim($entity->email)));

		// Default icon size
		$size = '150x150';

		// Use configured size if possible
		$config = elgg_get_icon_sizes('user');
		$key = $event->getParam('size');
		if (isset($config[$key])) {
			$size = $config[$key]['w'] . 'x' . $config[$key]['h'];
		}

		// Produce URL used to retrieve icon
		return "http://www.gravatar.com/avatar/$hash?s=$size";
	}

**entity:<icon_type>:url, <entity_type>** |results|
	Allows filtering URLs for custom icon types, see ``entity:icon:url, <entity_type>``

**entity:icon:file, <entity_type>** |results|
	Triggered by ``ElggEntity::getIcon()`` and allows plugins to provide an alternative ``ElggIcon`` object
	that points to a custom location of the icon on filestore. The handler must return an instance of ``ElggIcon``
	or an exception will be thrown.

**entity:<icon_type>:file, <entity_type>** |results|
	Allows filtering icon file object for custom icon types, see ``entity:icon:file, <entity_type>``

**entity:<icon_type>:prepare, <entity_type>** |results|
	Triggered by ``ElggEntity::saveIcon*()`` methods and can be used to prepare an image from uploaded/linked file.
	This event can be used to e.g. rotate the image before it is resized/cropped, or it can be used to extract an image frame
	if the uploaded file is a video. The handler must return an instance of ``ElggFile`` with a `simpletype`
	that resolves to `image`. The ``$return`` value passed to the event is an instance of ``ElggFile`` that points
	to a temporary copy of the uploaded/linked file.

	The ``$params`` array contains:

	 * ``entity`` - entity that owns the icons
	 * ``file`` - original input file before it has been modified by other events

**entity:<icon_type>:save, <entity_type>** |results|
	Triggered by ``ElggEntity::saveIcon*()`` methods and can be used to apply custom image manipulation logic to
	resizing/cropping icons. The handler must return ``true`` to prevent the core APIs from resizing/cropping icons.
	The ``$params`` array contains:

	 * ``entity`` - entity that owns the icons
	 * ``file`` - ``ElggFile`` object that points to the image file to be used as source for icons
	 * ``x1``, ``y1``, ``x2``, ``y2`` - cropping coordinates

**entity:<icon_type>:saved, <entity_type>** |results|
	Triggered by ``ElggEntity::saveIcon*()`` methods once icons have been created. This event can be used by plugins
	to create river items, update cropping coordinates for custom icon types etc. The handler can access the
	created icons using ``ElggEntity::getIcon()``.
	The ``$params`` array contains:

	 * ``entity`` - entity that owns the icons
	 * ``x1``, ``y1``, ``x2``, ``y2`` - cropping coordinates

**entity:<icon_type>:delete, <entity_type>** |results|
	Triggered by ``ElggEntity::deleteIcon()`` method and can be used for clean up operations. This event is triggered
	before the icons are deleted. The handler can return ``false`` to prevent icons from being deleted.
	The ``$params`` array contains:

	 * ``entity`` - entity that owns the icons

**entity:url, <entity_type>** |results|
	Return the URL for the entity ``$params['entity']``. Note: Generally it is better to override the
	``getUrl()`` method of ElggEntity. This event should be used when it's not possible to subclass
	(like if you want to extend a bundled plugin without overriding many views).

**extender:url, <annotation|metadata>** |results|
	Return the URL for the annotation or metadata ``$params['extender']``.

**fields, <entity_type>:<entity_subtype>** |results|
	Return an array of fields usable for ``elgg_view_field()``. The result should be returned as an array of fields. 
	It is required to provide ``name`` and ``#type`` for each field.

.. code-block:: php

	$result = [];
	
	$result[] = [
		'#type' => 'longtext',
		'name' => 'description',
	];
	
	return $result;

**get_list, default_widgets** |results|
	Filters a list of default widgets to add for newly registered users. The list is an array
	of arrays in the format:

.. code-block:: php

	array(
		'name' => elgg_echo('name'),
		'widget_columns' => 3,
		'widget_context' => $widget_context,
		
		'event_name' => $event_name,
		'event_type' => $event_type,
		
		'entity_type' => $entity_type,
		'entity_subtype' => $entity_subtype,
	)
	
**handlers, widgets** |results|
	Triggered when a list of available widgets is needed. Plugins can conditionally add or remove widgets from this list
	or modify attributes of existing widgets like ``context`` or ``multiple``.

**maintenance:allow, url** |results|
    Return boolean if the URL ``$params['current_url']`` and the path ``$params['current_path']``
	is allowed during maintenance mode.

**plugin_setting, <entity type>** |results|
	Can be used to change the value of the setting being saved
	
	Params contains:
	- ``entity`` - The ``ElggEntity`` where the plugin setting is being saved
	- ``plugin_id`` - The ID of the plugin for which the setting is being saved
	- ``name`` - The name of the setting being saved
	- ``value`` - The original value of the setting being saved
	
	Return value should be a scalar in order to be able to save it to the database. An error will be logged if this is not the case.

**public_pages, walled_garden** |results|
	Filters a list of URLs (paths) that can be seen by logged out users in a walled garden mode.
	Handlers must return an array of regex strings that will allow access if matched.
	Please note that system public routes are passed as the default value to the event,
	and plugins must take care to not accidentally override these values.

	The ``$params`` array contains:

	 * ``url`` - URL of the page being tested for public accessibility
	 
**relationship:url, <relationship_name>** |results|
	Filter the URL for the relationship object ``$params['relationship']``.

**robots.txt, site** |results|
	Filter the robots.txt values for ``$params['site']``.
	
**setting, plugin** |results|
	Filter plugin settings. ``$params`` contains:

	- ``plugin`` - An ElggPlugin instance
	- ``plugin_id`` - The plugin ID
	- ``name`` - The name of the setting
	- ``value`` - The value to set
	
**to:object, <entity_type|metadata|annotation|relationship|river_item>**
	Converts the entity ``$params['entity']`` to a StdClass object. This is used mostly for exporting
	entity properties for portable data formats like JSON and XML.

Plugins
=======

Groups
------

**tool_options, group** |results|
	Filters a collection of tools available within a specific group:

	The ``$return`` is ``\Elgg\Collections\Collection<\Elgg\Groups\Tool>``, a collection of group tools.

	The ``$params`` array contains:

	 * ``entity`` - ``\ElggGroup``

Web Services
------------

**register, api_methods``** |results|
    Triggered when the ApiRegistrationService is constructed which allows to add/remove/edit webservice configurations

**rest, init** |results|
	Triggered by the web services rest handler. Plugins can set up their own authentication
	handlers, then return ``true`` to prevent the default handlers from being registered.

**rest:output, <method_name>** |results|
	Filter the result (and subsequently the output) of the API method


.. |sequence| image:: https://raster.shields.io/badge/sequence-blue.png
.. |results| image:: https://raster.shields.io/badge/expects%20results-brightgreen.png
