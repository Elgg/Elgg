List of plugin hooks in core
############################

For more information on how hooks work visit :doc:`/design/events`.

.. contents:: Contents
    :local:
   :depth: 1

System hooks
============

**page_owner, system**
	Filter the page_owner for the current page. No options are passed.

**siteid, system**

**gc, system**
	Allows plugins to run garbage collection for ``$params['period']``.

**diagnostics:report, system**
	Filter the output for the diagnostics report download.

**cron, <period>**
	Triggered by cron for each period.

**cron:intervals, system**
	Allow the configuration of custom cron intervals

**validate, input**
	Filter GET and POST input. This is used by ``get_input()`` to sanitize user input.

**prepare, html**
	Triggered by ``elgg_format_html()`` and used to prepare untrusted HTML.

	The ``$return`` value is an array:

	 * ``html`` - HTML string being prepared
	 * ``options`` - Preparation options

**diagnostics:report, system**
	Filters the output for a diagnostic report.

**debug, log**
	Triggered by the Logger. Return false to stop the default logging method. ``$params`` includes:

	* level - The debug level. One of:
		* ``Elgg_Logger::OFF``
		* ``Elgg_Logger::ERROR``
		* ``Elgg_Logger::WARNING``
		* ``Elgg_Logger::NOTICE``
		* ``Elgg_Logger::INFO``
	* msg - The message
	* display - Should this message be displayed?

**format, friendly:title**
	Formats the "friendly" title for strings. This is used for generating URLs.

**format, friendly:time**
	Formats the "friendly" time for the timestamp ``$params['time']``.

**format, strip_tags**
	Filters a string to remove tags. The original string is passed as ``$params['original_string']``
	and an optional set of allowed tags is passed as ``$params['allowed_tags']``.

**output:before, page**
    In ``elgg_view_page()``, this filters ``$vars`` before it's passed to the page shell
    view (``page/<page_shell>``). To stop sending the X-Frame-Options header, unregister the
    handler ``Elgg\Page\SetXFrameOptionsHeaderHandler::class`` from this hook.

**output, page**
    In ``elgg_view_page()``, this filters the output return value.

**parameters, menu:<menu_name>**
	Triggered by ``elgg_view_menu()``. Used to change menu variables (like sort order) before rendering.

	The ``$params`` array will contain:

	 * ``name`` - name of the menu
	 * ``sort_by`` - preferring sorting parameter
	 * other parameters passed to ``elgg_view_menu()``

**register, menu:<menu_name>**
	Filters the initial list of menu items pulled from configuration, before the menu has been split into
	sections. Triggered by ``elgg_view_menu()`` and ``elgg()->menus->getMenu()``.

	The ``$params`` array will contain parameters returned by ``parameters, menu:<menu_name>`` hook.

	The return value is an instance of ``\Elgg\Menu\MenuItems`` containing ``\ElggMenuItem`` objects.

	Hook handlers can add/remove items to the collection using the collection API, as well as array access operations.

**register, menu:<menu_name>:<type>:<subtype>**
	More granular version of the menu hook triggered before the **register, menu:<menu_name>** hook.
	
	Only applied if menu params contain
	- params['entity'] with an ``\ElggEntity`` (``<type>`` is ``\ElggEntity::type`` and ``<subtype>`` is ``\ElggEntity::subtype``) or
	- params['annotation'] with an ``\ElggAnnotation`` (``<type>`` is ``\ElggAnnotation::getType()`` and ``<subtype>`` is ``\ElggAnnotation::getSubtype()``) or
	- params['relationship'] with an ``\ElggRelationship`` (``<type>`` is ``\ElggRelationship::getType()`` and ``<subtype>`` is ``\ElggRelationship::getSubtype()``)

**prepare, menu:<menu_name>**
	Filters the array of menu sections before they're displayed. Each section is a string key mapping to
	an area of menu items. This is a good hook to sort, add, remove, and modify menu items. Triggered by
	``elgg_view_menu()`` and ``elgg()->menus->prepareMenu()``.

	The ``$params`` array will contain:

	 * ``selected_item`` - ``ElggMenuItem`` selected in the menu, if any

	The return value is an instance of ``\Elgg\Menu\PreparedMenu``. The prepared menu is a collection of ``\Elgg\Menu\MenuSection``,
	which in turn are collections of ``\ElggMenuItem`` objects.

**prepare, menu:<menu_name>:<type>:<subtype>**
	More granular version of the menu hook triggered before the **prepare, menu:<menu_name>** hook.
	
	Only applied if menu params contain
	- params['entity'] with an ``\ElggEntity`` (``<type>`` is ``\ElggEntity::type`` and ``<subtype>`` is ``\ElggEntity::subtype``) or
	- params['annotation'] with an ``\ElggAnnotation`` (``<type>`` is ``\ElggAnnotation::getType()`` and ``<subtype>`` is ``\ElggAnnotation::getSubtype()``) or
	- params['relationship'] with an ``\ElggRelationship`` (``<type>`` is ``\ElggRelationship::getType()`` and ``<subtype>`` is ``\ElggRelationship::getSubtype()``)

**register, menu:filter:<filter_id>**
	Allows plugins to modify layout filter tabs on layouts that specify ``<filter_id>`` parameter. Parameters and return values
	are same as in ``register, menu:<menu_name>`` hook.
	
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

**creating, river**
	The options for ``elgg_create_river_item`` are filtered through this hook. You may alter values
	or return ``false`` to cancel the item creation.

**simplecache:generate, <view>**
	Filters the view output for a ``/cache`` URL when simplecache is enabled.

**cache:generate, <view>**
	Filters the view output for a ``/cache`` URL when simplecache is disabled. Note this will be fired
	for every ``/cache`` request--no Expires headers are used when simplecache is disabled.

**prepare, breadcrumbs**
    In ``elgg_get_breadcrumbs()``, this filters the registered breadcrumbs before
    returning them, allowing a plugin to alter breadcrumb strategy site-wide.
    ``$params`` array includes:

      * ``breadcrumbs`` - an array of bredcrumbs, each with ``title`` and ``link`` keys
      * ``identifier`` - route identifier of the current page
      * ``segments`` - route segments of the current page

**elgg.data, site**
   Filters cached configuration data to pass to the client. :ref:`More info <guides/javascript#config>`

**elgg.data, page**
   Filters uncached, page-specific configuration data to pass to the client. :ref:`More info <guides/javascript#config>`

**registration_url, site**
   Filters site's registration URL. Can be used by plugins to attach invitation codes, referrer codes etc. to the registration URL.
   ``$params`` array contains an array of query elements added to the registration URL by the invoking script.
   The hook must return an absolute URL to the registration page.

**login_url, site**
   Filters site's login URL.
   ``$params`` array contains an array of query elements added to the login URL by the invoking script.
   The hook must return an absolute URL of the login page.

**commands, cli**
   Allows plugins to register their own commands executable via ``elgg-cli`` binary.
   Handlers must return an array of command class names. Commands must extend ``\Elgg\Cli\Command`` to be executable.

**seeds, database**
   Allows plugins to register their own database seeds. Seeds populate the database with fake entities for testing purposes.
   Seeds must extend ``\Elgg\Database\Seeds\Seed`` class to be executable via ``elgg-cli database:seed``.

**languages, translations**
   Allows plugins to add/remove languages from the configurable languages in the system.

**generate, password**
	Allows plugins to generate new random cleartext passwords. 

User hooks
==========

**usersettings:save, user**
	Triggered in the aggregate action to save user settings.
	The hook handler must return ``false`` to prevent sticky forms from being cleared (i.e. to indicate that some of the values were not saved).
	Do not return ``true`` from your hook handler, as you will override other hooks' output, instead return ``null`` to indicate successful operation.

	The ``$params`` array will contain:

	 * ``user`` - ``\ElggUser``, whose settings are being saved
	 * ``request`` - ``\Elgg\Request`` to the action controller

**change:email, user**
	Triggered before the user email is changed.
	Allows plugins to implement additional logic required to change email, e.g. additional email validation.
	The hook handler must return false to prevent the email from being changed right away.

	The ``$params`` array will contain:

	 * ``user`` - ``\ElggUser``, whose settings are being saved
	 * ``email`` - Email address that passes sanity checks
	 * ``request`` - ``\Elgg\Request`` to the action controller

**access:collections:write, user**
	Filters an array of access permissions that the user ``$params['user_id']`` is allowed to save
	content with. Permissions returned are of the form (id => 'Human Readable Name').

**registeruser:validate:username, all**
	Return boolean for if the string in ``$params['username']`` is valid for a username.
	Hook handler can throw ``\Elgg\Exceptions\Configuration\RegistrationException`` with an error message to be shown to the user.

**registeruser:validate:password, all**
	Return boolean for if the string in ``$params['password']`` is valid for a password.
	Hook handler can throw ``\Elgg\Exceptions\Configuration\RegistrationException`` with an error message to be shown to the user.

**registeruser:validate:email, all**
	Return boolean for if the string in ``$params['email']`` is valid for an email address.
	Hook handler can throw ``\Elgg\Exceptions\Configuration\RegistrationException`` with an error message to be shown to the user.

**register, user**
	Triggered by the ``register`` action after the user registers. Return ``false`` to delete the user.
	Note the function ``register_user`` does *not* trigger this hook.
	Hook handlers can throw ``\Elgg\Exceptions\Configuration\RegistrationException`` with an error message to be displayed to the user.

	The ``$params`` array will contain:

	 * ``user`` - Newly registered user entity
	 * All parameters sent with the request to the action (incl. ``password``, ``friend_guid``, ``invitecode`` etc)

**login:forward, user**
    Filters the URL to which the user will be forwarded after login.

**find_active_users, system**
	Return the number of active users.

**status, user**
	Triggered by The Wire when adding a post.

**username:character_blacklist, user**
	Filters the string of blacklisted characters used to validate username during registration.
	The return value should be a string consisting of the disallowed characters. The default
	string can be found from ``$params['blacklist']``.

Object hooks
============

**comments, <entity_type>**
	Triggered in ``elgg_view_comments()``. If returning content, this overrides the
	``page/elements/comments`` view.

**comments:count, <entity_type>**
	Return the number of comments on ``$params['entity']``.

**likes:count, <entity_type>**
	Return the number of likes for ``$params['entity']``.


.. _guides/hooks-list#access-hooks:

Access hooks
============

**access_collection:url, access_collection**
	Can be used to filter the URL of the access collection.

	The ``$params`` array will contain:

	 * ``access_collection`` - `ElggAccessCollection`

**access_collection:name, access_collection**
	Can be used to filter the display name (readable access level) of the access collection.

	The ``$params`` array will contain:

	 * ``access_collection`` - `ElggAccessCollection`

**access:collections:read, user**
	Filters an array of access IDs that the user ``$params['user_id']`` can see.

	.. warning:: The handler needs to either not use parts of the API that use the access system (triggering the hook again) or to ignore the second call. Otherwise, an infinite loop will be created.

**access:collections:write, user**
	Filters an array of access IDs that the user ``$params['user_id']`` can write to. In
	get_write_access_array(), this hook filters the return value, so it can be used to alter
	the available options in the input/access view. For core plugins, the value "input_params"
	has the keys "entity" (ElggEntity|false), "entity_type" (string), "entity_subtype" (string),
	"container_guid" (int) are provided. An empty entity value generally means the form is to
	create a new object.

	.. warning:: The handler needs to either not use parts of the API that use the access system (triggering the hook again) or to ignore the second call. Otherwise, an infinite loop will be created.

**access:collections:write:subtypes, user**
	Returns an array of access collection subtypes to be used when retrieving access collections owned by a user as part of the ``get_write_access_array()`` function.
	
**access:collections:addcollection, collection**
	Triggered after an access collection ``$params['collection_id']`` is created.

**access:collections:deletecollection, collection**
	Triggered before an access collection ``$params['collection_id']`` is deleted.
	Return false to prevent deletion.

**access:collections:add_user, collection**
	Triggered before adding user ``$params['user_id']`` to collection ``$params['collection_id']``.
	Return false to prevent adding.

**access:collections:remove_user, collection**
	Triggered before removing user ``$params['user_id']`` to collection ``$params['collection_id']``.
	Return false to prevent removal.

**get_sql, access**
	Filters SQL clauses restricting/allowing access to entities and annotations.

	**The hook is triggered regardless if the access is ignored**. The handlers may need to check if access is ignored and return early, if appended clauses should only apply to access controlled contexts.

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


Action hooks
============

**action:validate, <action>**
	Trigger before action script/controller is executed.
	This hook should be used to validate/alter user input, before proceeding with the action.
	The hook handler can throw an instance of ``\Elgg\Exceptions\Http\ValidationException`` or return ``false``
	to terminate further execution.

    ``$params`` array includes:

     * ``request`` - instance of ``\Elgg\Request``

**action_gatekeeper:permissions:check, all**
	Triggered after a CSRF token is validated. Return false to prevent validation.

**action_gatekeeper:upload_exceeded_msg, all**
	Triggered when a POST exceeds the max size allowed by the server. Return an error message
	to display.

**forward, <reason>**
	Filter the URL to forward a user to when ``forward($url, $reason)`` is called.
	In certain cases, the ``params`` array will contain an instance of ``\Elgg\Exceptions\HttpException`` that triggered the error.

**response, action:<action>**
    Filter an instance of ``\Elgg\Http\ResponseBuilder`` before it is sent to the client.
    This hook can be used to modify response content, status code, forward URL, or set additional response headers.
    Note that the ``<action>`` value is parsed from the request URL, therefore you may not be able to filter
    the responses of `action()` calls if they are nested within the another action script file.

.. _guides/hooks-list#ajax:

Ajax
====

**ajax_response, \***
	When the ``elgg/Ajax`` AMD module is used, this hook gives access to the response object
	(``\Elgg\Services\AjaxResponse``) so it can be altered/extended. The hook type depends on
	the method call:

	================  ====================
	elgg/Ajax method  plugin hook type
	================  ====================
	action()          action:<action_name>
	path()            path:<url_path>
	view()            view:<view_name>
	form()            form:<action_name>
	================  ====================

.. _guides/hooks-list#permission-hooks:

Permission hooks
================

**container_logic_check, <entity_type>**
	Triggered by ``ElggEntity:canWriteToContainer()`` before triggering ``permissions_check`` and ``container_permissions_check``
	hooks. Unlike permissions hooks, logic check can be used to prevent certain entity types from being contained
	by other entity types, e.g. discussion replies should only be contained by discussions. This hook can also be
	used to apply status logic, e.g. do disallow new replies for closed discussions.

	The handler should return ``false`` to prevent an entity from containing another entity. The default value passed to the hook
	is ``null``, so the handler can check if another hook has modified the value by checking if return value is set.
	Should this hook return ``false``, ``container_permissions_check`` and ``permissions_check`` hooks will not be triggered.

	The ``$params`` array will contain:

	 * ``container`` - An entity that will be used as a container
	 * ``user`` - User who will own the entity to be written to container
	 * ``subtype`` - Subtype of the entity to be written to container (entity type is assumed from hook type)

**container_permissions_check, <entity_type>**
	Return boolean for if the user ``$params['user']`` can use the entity ``$params['container']``
	as a container for an entity of ``<entity_type>`` and subtype ``$params['subtype']``.

	In the rare case where an entity is created with neither the ``container_guid`` nor the ``owner_guid``
	matching the logged in user, this hook is called *twice*, and in the first call ``$params['container']``
	will be the *owner*, not the entity's real container.

	The ``$params`` array will contain:

	 * ``container`` - An entity that will be used as a container
	 * ``user`` - User who will own the entity to be written to container
	 * ``subtype`` - Subtype of the entity to be written to container (entity type is assumed from hook type)

**permissions_check, <entity_type>**
	Return boolean for if the user ``$params['user']`` can edit the entity ``$params['entity']``.

**permissions_check:delete, <entity_type>**
	Return boolean for if the user ``$params['user']`` can delete the entity ``$params['entity']``. Defaults to ``$entity->canEdit()``.

**permissions_check:delete, river**
	Return boolean for if the user ``$params['user']`` can delete the river item ``$params['item']``. Defaults to
	``true`` for admins and ``false`` for other users.

**permissions_check:download, file**
	Return boolean for if the user ``$params['user']`` can download the file in ``$params['entity']``.

	The ``$params`` array will contain:

	 * ``entity`` - Instance of ``ElggFile``
	 * ``user`` - User who will download the file

**permissions_check, widget_layout**
	Return boolean for if ``$params['user']`` can edit the widgets in the context passed as
	``$params['context']`` and with a page owner of ``$params['page_owner']``.

**permissions_check:comment, <entity_type>**
	Return boolean for if the user ``$params['user']`` can comment on the entity ``$params['entity']``.

**permissions_check:annotate:<annotation_name>, <entity_type>**
	Return boolean for if the user ``$params['user']`` can create an annotation ``<annotation_name>`` on the
	entity ``$params['entity']``. If logged in, the default is true.

	.. note:: This is called before the more general ``permissions_check:annotate`` hook, and its return value is that hook's initial value.

**permissions_check:annotate, <entity_type>**
	Return boolean for if the user ``$params['user']`` can create an annotation ``$params['annotation_name']``
	on the entity ``$params['entity']``. if logged in, the default is true.

**permissions_check:annotation**
	Return boolean for if the user in ``$params['user']`` can edit the annotation ``$params['annotation']`` on the
	entity ``$params['entity']``. The user can be null.

**fail, auth**
	Return the failure message if authentication failed. An array of previous PAM failure methods
	is passed as ``$params``.

**api_key, use**
	Triggered by ``elgg_ws_pam_auth_api_key()``. Returning false prevents the key from being authenticated.

**gatekeeper, <entity_type>:<entity_subtype>**
    Filters the result of ``elgg_entity_gatekeeper()`` to prevent or allow access to an entity that user would otherwise have or not have access to.
    A handler can return ``false`` or an instance of ``\Elgg\Exceptions\HttpException`` to prevent access to an entity.
    A handler can return ``true`` to override the result of the gatekeeper.
    **Important** that the entity received by this hook is fetched with ignored access and including disabled entities,
    so you have to be careful to not bypass the access system.

    ``$params`` array includes:

	 * ``entity`` - Entity that is being accessed
	 * ``user`` - User accessing the entity (``null`` implies logged in user)


Notifications
=============

These hooks are listed chronologically in the lifetime of the notification event.
Note that not all hooks apply to instant notifications.

**enqueue, notification**
	Can be used to prevent a notification event from sending **subscription** notifications.
	Hook handler must return ``false`` to prevent a subscription notification event from being enqueued.

	``$params`` array includes:

	 * ``object`` - object of the notification event
	 * ``action`` - action that triggered the notification event. E.g. corresponds to ``publish`` when ``elgg_trigger_event('publish', 'object', $object)`` is called

**get, subscriptions**
	Filters subscribers of the notification event.
	Applies to **subscriptions** and **instant** notifications.
	In case of a subscription event, by default, the subscribers list consists of the users subscribed to the container entity of the event object.
	In case of an instant notification event, the subscribers list consists of the users passed as recipients to ``notify_user()``

   **IMPORTANT** Always validate the notification event, object and/or action types before adding any new recipients to ensure that you do not accidentally dispatch notifications to unintended recipients.
   Consider a situation, where a mentions plugin sends out an instant notification to a mentioned user - any hook acting on a subject or an object without validating an event or action type (e.g. including an owner of the original wire thread) might end up sending notifications to wrong users.

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


**send:before, notifications**
	Triggered before the notification event queue is processed. Can be used to terminate the notification event.
	Applies to **subscriptions** and **instant** notifications.

	``$params`` array includes:

	 * ``event`` - ``\Elgg\Notifications\NotificationEvent`` instance that describes the notification event
	 * ``subscriptions`` - a list of subscriptions. See ``'get', 'subscriptions'`` hook for details

**prepare, notification**
	A high level hook that can be used to alter an instance of ``\Elgg\Notifications\Notification`` before it is sent to the user.
	Applies to **subscriptions** and **instant** notifications.
	This hook is triggered before a more granular ``'prepare', 'notification:<action>:<entity_type>:<entity_subtype>'`` and after ``'send:before', 'notifications``.
	Hook handler should return an altered notification object.

	``$params`` may vary based on the notification type and may include:

	 * ``event`` - ``\Elgg\Notifications\NotificationEvent`` instance that describes the notification event
	 * ``object`` - object of the notification ``event``. Can be ``null`` for instant notifications
	 * ``action`` - action that triggered the notification ``event``. May default to ``notify_user`` for instant notifications
	 * ``method`` - delivery method (e.g. ``email``, ``site``)
	 * ``sender`` - sender
	 * ``recipient`` - recipient
	 * ``language`` - language of the notification (recipient's language)
	 * ``origin`` - ``subscriptions_service`` or ``instant_notifications``

**prepare, notification:<action>:<entity_type>:<entity_type>**
	A granular hook that can be used to filter a notification ``\Elgg\Notifications\Notification`` before it is sent to the user.
	Applies to **subscriptions** and **instant** notifications.
	In case of instant notifications that have not received an object, the hook will be called as ``'prepare', 'notification:<action>'``.
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

**format, notification:<method>**
	This hook can be used to format a notification before it is passed to the ``'send', 'notification:<method>'`` hook.
	Applies to **subscriptions** and **instant** notifications.
	The hook handler should return an instance of ``\Elgg\Notifications\Notification``.
	The hook does not receive any ``$params``.
	Some of the use cases include:

	 * Strip tags from notification title and body for plaintext email notifications
	 * Inline HTML styles for HTML email notifications
	 * Wrap notification in a template, add signature etc.

**send, notification:<method>**
	Delivers a notification.
	Applies to **subscriptions** and **instant** notifications.
	The handler must return ``true`` or ``false`` indicating the success of the delivery.

	``$params`` array includes:

	 * ``notification`` - a notification object ``\Elgg\Notifications\Notification``

**send:after, notifications**
	Triggered after all notifications in the queue for the notifications event have been processed.
	Applies to **subscriptions** and **instant** notifications.

	``$params`` array includes:

	 * ``event`` - ``\Elgg\Notifications\NotificationEvent`` instance that describes the notification event
	 * ``subscriptions`` - a list of subscriptions. See ``'get', 'subscriptions'`` hook for details
	 * ``deliveries`` - a matrix of delivery statuses by user for each delivery method


Emails
======

**prepare, system:email**
	Triggered by ``elgg_send_email()``.
	Applies to all outgoing system and notification emails.
	This hook allows you to alter an instance of ``\Elgg\Email`` before it is passed to the email transport.
	This hook can be used to alter the sender, recipient, subject, body, and/or headers of the email.

	``$params`` are empty. The ``$return`` value is an instance of ``\Elgg\Email``.

**validate, system:email**
	Triggered by ``elgg_send_email()``.
	Applies to all outgoing system and notification emails.
	This hook allows you to suppress or whitelist outgoing emails, e.g. when the site is in a development mode.
	The handler must return ``false`` to supress the email delivery.

	``$params`` contains:

	 * ``email`` - An instance of ``\Elgg\Email``

**transport, system:email**
	Triggered by ``elgg_send_email()``.
	Applies to all outgoing system and notification emails.
	This hook allows you to implement a custom email transport, e.g. delivering emails via a third-party proxy service such as SendGrid or Mailgun.
	The handler must return ``true`` to indicate that the email was transported.

	``$params`` contains:

	 * ``email`` - An instance of ``\Elgg\Email``

**zend:message, system:email**
	Triggered by the default email transport handler (Elgg uses ``laminas/laminas-mail``).
	Applies to all outgoing system and notification emails that were not transported using the **transport, system:email** hook.
	This hook allows you to alter an instance of ``\Laminas\Mail\Message`` before it is passed to the Laminas email transport.

	``$params`` contains:

	 * ``email`` - An instance of ``\Elgg\Email``

Routing
=======

**route:config, <route_name>**
	Allows altering the route configuration before it is registered.
	This hook can be used to alter the path, default values, requirements, as well as to set/remove middleware.
	Please note that the handler for this hook should be registered outside of the ``init`` event handler, as core routes are registered during ``plugins_boot`` event.

**route:rewrite, <identifier>**
	Allows altering the site-relative URL path for an incoming request. See :doc:`routing` for details.
	Please note that the handler for this hook should be registered outside of the ``init`` event handler, as route rewrites take place after ``plugins_boot`` event has completed.

**response, path:<path>**
    Filter an instance of ``\Elgg\Http\ResponseBuilder`` before it is sent to the client.
    This hook type will only be used if the path did not start with "action/" or "ajax/".
    This hook can be used to modify response content, status code, forward URL, or set additional response headers.
    Note that the ``<path>`` value is parsed from the request URL, therefore plugins using the ``route`` hook should
    use the original ``<path>`` to filter the response, or switch to using the ``route:rewrite`` hook.

**ajax_response, path:<path>**
    Filters ajax responses before they're sent back to the ``elgg/Ajax`` module. This hook type will
    only be used if the path did not start with "action/" or "ajax/".


.. _guides/hooks-list#views:

Views
=====

**view_vars, <view_name>**
	Filters the ``$vars`` array passed to the view

**view, <view_name>**
    Filters the returned content of the view

**layout, page**
    In ``elgg_view_layout()``, filters the layout name.
    ``$params`` array includes:

     * ``identifier`` - ID of the page being rendered
     * ``segments`` - URL segments of the page being rendered
     * other ``$vars`` received by ``elgg_view_layout()``

**shell, page**
    In ``elgg_view_page()``, filters the page shell name

**head, page**
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


**ajax_response, view:<view>**
    Filters ``ajax/view/`` responses before they're sent back to the ``elgg/Ajax`` module.

**ajax_response, form:<action>**
    Filters ``ajax/form/`` responses before they're sent back to the ``elgg/Ajax`` module.

**response, view:<view_name>**
    Filter an instance of ``\Elgg\Http\ResponseBuilder`` before it is sent to the client.
    Applies to request to ``/ajax/view/<view_name>``.
    This hook can be used to modify response content, status code, forward URL, or set additional response headers.

**response, form:<form_name>**
    Filter an instance of ``\Elgg\Http\ResponseBuilder`` before it is sent to the client.
    Applies to request to ``/ajax/form/<form_name>``.
    This hook can be used to modify response content, status code, forward URL, or set additional response headers.

**table_columns:call, <name>**
    When the method ``elgg()->table_columns->$name()`` is called, this hook is called to allow
    plugins to override or provide an implementation. Handlers receive the method arguments via
    ``$params['arguments']`` and should return an instance of ``Elgg\Views\TableColumn`` if they
    wish to specify the column directly.

**vars:compiler, css**
    Allows plugins to alter CSS variables passed to CssCrush during compilation.
    See `CSS variables <_guides/theming#css-vars>`.


Files
=====

**download:url, file**
    Allows plugins to filter the download URL of the file.
	By default, the download URL is generated by the file service.

    ``$params`` array includes:

     * ``entity`` - instance of ``ElggFile``

**inline:url, file**
    Allows plugins to filter the inline URL of the image file.
	By default, the inline URL is generated by the file service.

    ``$params`` array includes:

     * ``entity`` - instance of ``ElggFile``

**mime_type, file**
	Return the mimetype for the filename ``$params['filename']`` with original filename ``$params['original_filename']``
	and with the default detected mimetype of ``$params['default']``.

**simple_type, file**
    The hook provides ``$params['mime_type']`` (e.g. ``application/pdf`` or ``image/jpeg``) and determines an overall 
    category like ``document`` or ``image``. The bundled file plugin and other-third party plugins usually store
    ``simpletype`` metadata on file entities and make use of it when serving icons and constructing
    ``ege*`` filters and menus.

**upload, file**
    Allows plugins to implement custom logic for moving an uploaded file into an instance of ``ElggFile``.
    The handler must return ``true`` to indicate that the uploaded file was moved.
    The handler must return ``false`` to indicate that the uploaded file could not be moved.
    Other returns will indicate that ``ElggFile::acceptUploadedFile`` should proceed with the
    default upload logic.

    ``$params`` array includes:

     * ``file`` - instance of ``ElggFile`` to write to
     * ``upload`` - instance of Symfony's ``UploadedFile``


.. _guides/hooks-list#search:

Search
======

**search:results, <search_type>**
    Triggered by ``elgg_search()``. Receives normalized options suitable for ``elgg_get_entities()`` call and must return an array of entities matching search options.
    This hook is designed for use by plugins integrating third-party indexing services, such as Solr and Elasticsearch.

**search:params, <search_type>**
    Triggered by ``elgg_search()``. Filters search parameters (query, sorting, search fields etc) before search clauses are prepared for a given search type.
    Elgg core only provides support for ``entities`` search type.

**search:fields, <entity_type>**
    Triggered by ``elgg_search()``. Filters search fields before search clauses are prepared.
    ``$return`` value contains an array of names for each entity property type, which should be matched against the search query.
    ``$params`` array contains an array of search params passed to and filtered by ``elgg_search()``.

.. code-block:: php

    return [
        'attributes' => [],
        'metadata' => ['title', 'description'],
        'annotations' => ['revision'],
        'private_settings' => ['internal_notes'],
    ];

**search:fields, <entity_type>:<entity_subtype>**
   See **search:fields, <entity_type>**

**search:fields, <search_type>**
    See **search:fields, <entity_type>**

**search:options, <entity_type>**
    Triggered by ``elgg_search()``. Prepares search clauses (options) to be passed to ``elgg_get_entities()``.

**search:options, <entity_type>:<entity_subtype>**
    See **search:options, <entity_type>**

**search:options, <search_type>**
    See **search:options, <entity_type>**

**search:config, search_types**
    Implemented in the **search** plugin.
    Filters an array of custom search types. This allows plugins to add custom search types (e.g. tag or location search).
    Adding a custom search type will extend the search plugin user interface with appropriate links and lists.

**search:config, type_subtype_pairs**
    Implemented in the **search** plugin.
    Filters entity type/subtype pairs before entity search is performed.
    Allows plugins to remove certain entity types/subtypes from search results, group multiple subtypes together, or to reorder search sections.

**search:format, entity**
    Implemented in the **search** plugin.
    Allows plugins to populate entity's volatile data before it's passed to search view.
    This is used for highlighting search hit, extracting relevant substrings in long text fields etc.

.. _guides/hooks-list#other:

Other
=====

**config, comments_per_page**
	Filters the number of comments displayed per page. Default is 25. ``$params['entity']`` will hold
	the containing entity or null if not provided. Use ``elgg_comments_per_page()`` to get the value.

**config, comments_latest_first**
	Filters the order of comments. Default is ``true`` for latest first. ``$params['entity']`` will hold
	the containing entity or null if not provided.

**default, access**
	In get_default_access(), this hook filters the return value, so it can be used to alter
	the default value in the input/access view. For core plugins, the value "input_params" has
	the keys "entity" (ElggEntity|false), "entity_type" (string), "entity_subtype" (string),
	"container_guid" (int) are provided. An empty entity value generally means the form is to
	create a new object.

**classes, icon**
	Can be used to filter CSS classes applied to icon glyphs. By default, Elgg uses FontAwesome. Plugins can use this
	hook to switch to a different font family and remap icon classes.

**entity:icon:sizes, <entity_type>**
	Triggered by ``elgg_get_icon_sizes()`` and sets entity type/subtype specific icon sizes.
	``entity_subtype`` will be passed with the ``$params`` array to the callback.

**entity:<icon_type>:sizes, <entity_type>**
	Allows filtering sizes for custom icon types, see ``entity:icon:sizes, <entity_type>``.

	The hook must return an associative array where keys are the names of the icon sizes
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

**entity:icon:url, <entity_type>**
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
	elgg_register_plugin_hook_handler('entity:icon:url', 'user', 'gravatar_icon_handler', 600);

	/**
	 * Default to icon from gravatar for users without avatar.
	 *
	 * @param \Elgg\Hook $hook 'entity:icon:url', 'user'
	 *
	 * @return string
	 */
	function gravatar_icon_handler(\Elgg\Hook $hook) {
		$entity = $hook->getEntityParam();
		
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
		$key = $hook->getParam('size');
		if (isset($config[$key])) {
			$size = $config[$key]['w'] . 'x' . $config[$key]['h'];
		}

		// Produce URL used to retrieve icon
		return "http://www.gravatar.com/avatar/$hash?s=$size";
	}

**entity:<icon_type>:url, <entity_type>**
	Allows filtering URLs for custom icon types, see ``entity:icon:url, <entity_type>``

**entity:icon:file, <entity_type>**
	Triggered by ``ElggEntity::getIcon()`` and allows plugins to provide an alternative ``ElggIcon`` object
	that points to a custom location of the icon on filestore. The handler must return an instance of ``ElggIcon``
	or an exception will be thrown.

**entity:<icon_type>:file, <entity_type>**
	Allows filtering icon file object for custom icon types, see ``entity:icon:file, <entity_type>``

**entity:<icon_type>:prepare, <entity_type>**
	Triggered by ``ElggEntity::saveIcon*()`` methods and can be used to prepare an image from uploaded/linked file.
	This hook can be used to e.g. rotate the image before it is resized/cropped, or it can be used to extract an image frame
	if the uploaded file is a video. The handler must return an instance of ``ElggFile`` with a `simpletype`
	that resolves to `image`. The ``$return`` value passed to the hook is an instance of ``ElggFile`` that points
	to a temporary copy of the uploaded/linked file.

	The ``$params`` array contains:

	 * ``entity`` - entity that owns the icons
	 * ``file`` - original input file before it has been modified by other hooks

**entity:<icon_type>:save, <entity_type>**
	Triggered by ``ElggEntity::saveIcon*()`` methods and can be used to apply custom image manipulation logic to
	resizing/cropping icons. The handler must return ``true`` to prevent the core APIs from resizing/cropping icons.
	The ``$params`` array contains:

	 * ``entity`` - entity that owns the icons
	 * ``file`` - ``ElggFile`` object that points to the image file to be used as source for icons
	 * ``x1``, ``y1``, ``x2``, ``y2`` - cropping coordinates

**entity:<icon_type>:saved, <entity_type>**
	Triggered by ``ElggEntity::saveIcon*()`` methods once icons have been created. This hook can be used by plugins
	to create river items, update cropping coordinates for custom icon types etc. The handler can access the
	created icons using ``ElggEntity::getIcon()``.
	The ``$params`` array contains:

	 * ``entity`` - entity that owns the icons
	 * ``x1``, ``y1``, ``x2``, ``y2`` - cropping coordinates

**entity:<icon_type>:delete, <entity_type>**
	Triggered by ``ElggEntity::deleteIcon()`` method and can be used for clean up operations. This hook is triggered
	before the icons are deleted. The handler can return ``false`` to prevent icons from being deleted.
	The ``$params`` array contains:

	 * ``entity`` - entity that owns the icons

**entity:url, <entity_type>**
	Return the URL for the entity ``$params['entity']``. Note: Generally it is better to override the
	``getUrl()`` method of ElggEntity. This hook should be used when it's not possible to subclass
	(like if you want to extend a bundled plugin without overriding many views).

**fields, <entity_type>:<entity_subtype>**
	Return an array of fields usable for ``elgg_view_field()``. The result should be returned as an array of fields. 
	It is required to provide ``name`` and ``#type`` for each field.

.. code-block:: php

	$result = [];
	
	$result[] = [
		'#type' => 'longtext',
		'name' => 'description',
	];
	
	return $result;

**to:object, <entity_type|metadata|annotation|relationship|river_item>**
	Converts the entity ``$params['entity']`` to a StdClass object. This is used mostly for exporting
	entity properties for portable data formats like JSON and XML.

**extender:url, <annotation|metadata>**
	Return the URL for the annotation or metadatum ``$params['extender']``.

**file:icon:url, override**
	Override a file icon URL.

**is_member, group**
	Return boolean for if the user ``$params['user']`` is a member of the group ``$params['group']``.

**setting, plugin**
	Filter plugin settings. ``$params`` contains:

	- ``plugin`` - An ElggPlugin instance
	- ``plugin_id`` - The plugin ID
	- ``name`` - The name of the setting
	- ``value`` - The value to set

**plugin_setting, <entity type>**
	Can be used to change the value of the setting being saved
	
	Params contains:
	- ``entity`` - The ``ElggEntity`` where the plugin setting is being saved
	- ``plugin_id`` - The ID of the plugin for which the setting is being saved
	- ``name`` - The name of the setting being saved
	- ``value`` - The original value of the setting being saved
	
	Return value should be a scalar in order to be able to save it to the database. An error will be logged if this is not the case.

**relationship:url, <relationship_name>**
	Filter the URL for the relationship object ``$params['relationship']``.

**widget_settings, <widget_handler>**
	Triggered when saving a widget settings ``$params['params']`` for widget ``$params['widget']``.
	If handling saving the settings, the handler should return true to prevent the default code from running.

**handlers, widgets**
	Triggered when a list of available widgets is needed. Plugins can conditionally add or remove widgets from this list
	or modify attributes of existing widgets like ``context`` or ``multiple``.

**get_list, default_widgets**
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

**public_pages, walled_garden**
	Filters a list of URLs (paths) that can be seen by logged out users in a walled garden mode.
	Handlers must return an array of regex strings that will allow access if matched.
	Please note that system public routes are passed as the default value to the hook,
	and plugins must take care to not accidentally override these values.

	The ``$params`` array contains:

	 * ``url`` - URL of the page being tested for public accessibility

**volatile, metadata**
	Triggered when exporting an entity through the export handler. This is rare.
	This allows handler to handle any volatile (non-persisted) metadata on the entity.
	It's preferred to use the ``to:object, <type>`` hook.

**maintenance:allow, url**
    Return boolean if the URL ``$params['current_url']`` and the path ``$params['current_path']``
	is allowed during maintenance mode.

**robots.txt, site**
	Filter the robots.txt values for ``$params['site']``.

**config, amd**
	Filter the AMD config for the requirejs library.

Plugins
=======

Embed
-----

**embed_get_items, <active_section>**

**embed_get_sections, all**

**embed_get_upload_sections, all**

Groups
------

**profile_buttons, group**
	Filters buttons (``ElggMenuItem`` instances) to be registered in the title menu of the group profile page

**tool_options, group**
	Filters a collection of tools available within a specific group:

	The ``$return`` is ``\Elgg\Collections\Collection<\Elgg\Groups\Tool>``, a collection of group tools.

	The ``$params`` array contains:

	 * ``entity`` - ``\ElggGroup``

HTMLawed
--------

**allowed_styles, htmlawed**
	Filter the HTMLawed allowed style array.

**config, htmlawed**
	Filter the HTMLawed ``$config`` array.

**spec, htmlawed**
	Filter the HTMLawed ``$spec`` string (default empty).

Likes
-----

**likes:is_likable, <type>:<subtype>**
    This is called to set the default permissions for whether to display/allow likes on an entity of type
    ``<type>`` and subtype ``<subtype>``.

    .. note:: The callback ``'Elgg\Values::getTrue'`` is a useful handler for this hook.

Members
-------

**members:list, <page_segment>**
    To handle the page ``/members/$page_segment``, register for this hook and return the HTML of the list.

**members:config, tabs**
    This hook is used to assemble an array of tabs to be passed to the navigation/tabs view
    for the members pages.

Web Services
------------

**rest, init**
	Triggered by the web services rest handler. Plugins can set up their own authentication
	handlers, then return ``true`` to prevent the default handlers from being registered.

**rest:output, <method_name>**
	Filter the result (and subsequently the output) of the API method
