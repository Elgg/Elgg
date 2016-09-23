List of plugin hooks in core
############################

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

**unit_test, system**
	Add a Simple Test test. (Deprecated.)

**diagnostics:report, system**
	Filter the output for the diagnostics report download.

**search_types, get_types**

**cron, <period>**
	Triggered by cron for each period.

**validate, input**
	Filter GET and POST input. This is used by ``get_input()`` to sanitize user input.

**geocode, location**
	Deprecated as of 1.9.

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
    handler ``_elgg_views_send_header_x_frame_options()`` from this hook.

**output, page**
    In ``elgg_view_page()``, this filters the output return value.

**output:before, layout**
	In ``elgg_view_layout()``, filters ``$params`` before it's passed to the layout view.

**output:after, layout**
	In ``elgg_view_layout()``, filters the return value of the layout view.

**parameters, menu:<menu_name>**
	Triggered by ``elgg_view_menu()``. Used to change menu variables (like sort order) before rendering.

**register, menu:<menu_name>**
	Filters the initial list of menu items pulled from configuration, before the menu has been split into
	sections. Triggered by ``elgg_view_menu()`` and ``elgg()->menus->getMenu()``.

**prepare, menu:<menu_name>**
	Filters the array of menu sections before they're displayed. Each section is a string key mapping to
	an area of menu items. This is a good hook to sort, add, remove, and modify menu items. Triggered by
	``elgg_view_menu()`` and ``elgg()->menus->prepareMenu()``.

**creating, river**
	The options for ``elgg_create_river_item`` are filtered through this hook. You may alter values
	or return ``false`` to cancel the item creation.

**simplecache:generate, <view>**
	Triggered when generating the cached content of a view.

**prepare, breadcrumbs**
    In elgg_get_breadcrumbs(), this filters the registered breadcrumbs before
    returning them, allowing a plugin to alter breadcrumb strategy site-wide.

**add, river**

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

User hooks
==========

**usersettings:save, user**
	Triggered in the aggregate action to save user settings. Return false prevent sticky
	forms from being cleared.

**access:collections:write, user**
	Filters an array of access permissions that the user ``$params['user_id']`` is allowed to save
	content with. Permissions returned are of the form (id => 'Human Readable Name').

**registeruser:validate:username, all**
	Return boolean for if the string in ``$params['username']`` is valid for a username.

**registeruser:validate:password, all**
	Return boolean for if the string in ``$params['password']`` is valid for a password.

**registeruser:validate:email, all**
	Return boolean for if the string in ``$params['email']`` is valid for an email address.

**register, user**
	Triggered by the ``register`` action after the user registers. Return ``false`` to delete the user.
	Note the function ``register_user`` does *not* trigger this hook.

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

Action hooks
============

**action, <action>**
	Triggered before executing action scripts. Return false to abort action.

**action_gatekeeper:permissions:check, all**
	Triggered after a CSRF token is validated. Return false to prevent validation.

**action_gatekeeper:upload_exceeded_msg, all**
	Triggered when a POST exceeds the max size allowed by the server. Return an error message
	to display.

**forward, <reason>**
	Filter the URL to forward a user to when ``forward($url, $reason)`` is called.

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

**output, ajax**
	This filters the JSON output wrapper returned to the legacy ajax API (``elgg.ajax``, ``elgg.action``, etc.).
	Plugins can alter the output, forward URL, system messages, and errors. For the ``elgg/Ajax`` AMD module,
	use the ``ajax_response`` hook documented above.


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

	.. note:: This check is not performed when using the deprecated ``elgg_delete_river()``.

**permissions_check, widget_layout**
	Return boolean for if ``$params['user']`` can edit the widgets in the context passed as
	``$params['context']`` and with a page owner of ``$params['page_owner']``.

**permissions_check:metadata, <entity_type>**
	Return boolean for if the user ``$params['user']`` can edit the metadata ``$params['metadata']``
	on the entity ``$params['entity']``.

**permissions_check:comment, <entity_type>**
	Return boolean for if the user ``$params['user']`` can comment on the entity ``$params['entity']``.

**permissions_check:annotate:<annotation_name>, <entity_type>**
	Return boolean for if the user ``$params['user']`` can create an annotation ``<annotation_name>`` on the
	entity ``$params['entity']``. If logged in, the default is true.

	.. note:: This is called before the more general ``permissions_check:annotate`` hook, and its return value is that hook's initial value.

**permissions_check:annotate, <entity_type>**
	Return boolean for if the user ``$params['user']`` can create an annotation ``$params['annotation_name']``
	on the entity ``$params['entity']``. if logged in, the default is true.

	.. warning:: This is functions differently than the ``permissions_check:metadata`` hook by passing the annotation name instead of the metadata object.

**permissions_check:annotation**
	Return boolean for if the user in ``$params['user']`` can edit the annotation ``$params['annotation']`` on the
	entity ``$params['entity']``. The user can be null.

**fail, auth**
	Return the failure message if authentication failed. An array of previous PAM failure methods
	is passed as ``$params``.

**api_key, use**
	Triggered by ``api_auth_key()``. Returning false prevents the key from being authenticated.

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
    Filters the SQL clauses used in ``_elgg_get_access_where_sql()``.

**gatekeeper, <entity_type>:<entity_subtype>**
    Filters the result of ``elgg_entity_gatekeeper()`` to prevent access to an entity that user would otherwise have access to. A handler should return false to deny access to an entity.


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

	``$params`` array includes:

	 * ``event`` - ``\Elgg\Notifications\NotificationEvent`` instance that describes the notification event
	 * ``origin`` - ``subscriptions_service`` or ``instant_notifications``
	 * ``methods_override`` - delivery method preference for instant notifications

	Handlers must return an array in the form:

.. code:: php

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

**email, system**
	Triggered by ``elgg_send_email()``.
	Applies to **subscriptions** and **instant** notifications with ``email`` method.
	This hook can be used to alter email parameters (subject, body, headers etc) - the handler should return an array of altered parameters.
	This hook can also be used to implement a custom email transport (in place of Elgg's default plaintext ``\Zend\Mail\Transport\Sendmail``) - the handler must return ``true`` or ``false`` to indicate whether the email was sent using a custom transport.

	``$params`` contains:

	 * ``to`` - email address or string in the form ``Name <name@example.org>`` of the recipient
	 * ``from`` - email address or string in the form ``Name <name@example.org>`` of the sender
	 * ``subject`` - subject line of the email
	 * ``body`` - body of the email
	 * ``headers`` - an array of headers
	 * ``params`` - other parameters inherited from the notification object or passed directly to ``elgg_send_email()``

**send:after, notifications**
	Triggered after all notifications in the queue for the notifications event have been processed.
	Applies to **subscriptions** and **instant** notifications.

	``$params`` array includes:

	 * ``event`` - ``\Elgg\Notifications\NotificationEvent`` instance that describes the notification event
	 * ``subscriptions`` - a list of subscriptions. See ``'get', 'subscriptions'`` hook for details
	 * ``deliveries`` - a matrix of delivery statuses by user for each delivery method


Routing
=======

**route, <identifier>**
    Allows applying logic or returning a response before the page handler is called. See :doc:`routing`
    for details.
    Note that plugins using this hook to rewrite paths, will not be able to filter the response object by
    its final path and should either switch to ``route:rewrite, <identifier>`` hook or use ``response, path:<path>`` hook for
    the original path.

**route:rewrite, <identifier>**
	Allows altering the site-relative URL path. See :doc:`routing` for details.

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
    In ``elgg_view_layout()``, filters the layout name

**shell, page**
    In ``elgg_view_page()``, filters the page shell name

**head, page**
    In ``elgg_view_page()``, filters ``$vars['head']``
    Return value contains an array with ``title``, ``metas`` and ``links`` keys,
    where ``metas`` is an array of elements to be formatted as ``<meta>`` head tags,
    and ``links`` is an array of elements to be formatted as ``<link>`` head tags.
    Each meta and link element contains a set of key/value pairs that are formatted
    into html tag attributes, e.g.

.. code:: php

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
		     'href' => elgg_get_simplecache_url('favicon-16.png'),
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

Files
=====

**mime_type, file**
	Return the mimetype for the filename ``$params['filename']`` with original filename ``$params['original_filename']``
	and with the default detected mimetype of ``$params['default']``.

**simple_type, file**
    In ``elgg_get_file_simple_type()``, filters the return value. The hook uses ``$params['mime_type']``
    (e.g. ``application/pdf`` or ``image/jpeg``) and determines an overall category like
    ``document`` or ``image``. The bundled file plugin and other-third party plugins usually store
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

.. _guides/hooks-list#other:

Other
=====

**config, comments_per_page**
	Filters the number of comments displayed per page. Default is 25.

**default, access**
	In get_default_access(), this hook filters the return value, so it can be used to alter
	the default value in the input/access view. For core plugins, the value "input_params" has
	the keys "entity" (ElggEntity|false), "entity_type" (string), "entity_subtype" (string),
	"container_guid" (int) are provided. An empty entity value generally means the form is to
	create a new object.

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
	 * ``upscale`` - Should the image be upscaled in case it is smaller than the given
           width and height (true/false)

	If the configuration array for an image size is empty, the image will be
	saved as an exact copy of the source without resizing or cropping.

	Example:

.. code:: php

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

.. code:: php

	// Priority 600 so that handler is triggered after avatar handler
	elgg_register_plugin_hook_handler('entity:icon:url', 'user', 'gravatar_icon_handler', 600);

	/**
	 * Default to icon from gravatar for users without avatar.
	 */
	function gravatar_icon_handler($hook, $type, $url, $params) {
		// Allow users to upload avatars
		if ($params['entity']->icontime) {
			return $url;
		}
		
		// Generate gravatar hash for user email
		$hash = md5(strtolower(trim($params['entity']->email)));
		
		// Default icon size
		$size = '150x150';

		// Use configured size if possible
		$config = elgg_get_icon_sizes('user');
		$key = $params['size'];
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

**to:object, <entity_type|metadata|annotation|relationship|river_item>**
	Converts the entity ``$params['entity']`` to a StdClass object. This is used mostly for exporting
	entity properties for portable data formats like JSON and XML.

**extender:url, <annotation|metadata>**
	Return the URL for the annotation or metadatum ``$params['extender']``.

**file:icon:url, override**
	Override a file icon URL.

**is_member, group**
	Return boolean for if the user ``$params['user']`` is a member of the group ``$params['group']``.

**entity:annotate, <entity_type>**
	Triggered in ``elgg_view_entity_annotations()``, which is called by ``elgg_view_entity()``. Can
	be used to add annotations to all full entity views.

**usersetting, plugin**
	Filter user settings for plugins. ``$params`` contains:

	- ``user`` - An ElggUser instance
	- ``plugin`` - An ElggPlugin instance
	- ``plugin_id`` - The plugin ID
	- ``name`` - The name of the setting
	- ``value`` - The value to set

**setting, plugin**
	Filter plugin settings. ``$params`` contains:

	- ``plugin`` - An ElggPlugin instance
	- ``plugin_id`` - The plugin ID
	- ``name`` - The name of the setting
	- ``value`` - The value to set

**relationship:url, <relationship_name>**
	Filter the URL for the relationship object ``$params['relationship']``.

**profile:fields, group**
	Filter an array of profile fields. The result should be returned as an array in the format
	``name => input view name``. For example:

.. code:: php

	array(
		'about' => 'longtext'
	);


**profile:fields, profile**
	Filter an array of profile fields. The result should be returned as an array in the format
	``name => input view name``. For example:

.. code:: php

	array(
		'about' => 'longtext'
	);

**widget_settings, <widget_handler>**
	Triggered when saving a widget settings ``$params['params']`` for widget ``$params['widget']``.
	If handling saving the settings, the handler should return true to prevent the default code from running.

**handlers, widgets**
	Triggered when a list of available widgets is needed. Plugins can conditionally add or remove widgets from this list
	or modify attributes of existing widgets like ``context`` or ``multiple``.

**get_list, default_widgets**
	Filters a list of default widgets to add for newly registered users. The list is an array
	of arrays in the format:

.. code:: php

	array(
		'event' => $event,
		'entity_type' => $entity_type,
		'entity_subtype' => $entity_subtype,
		'widget_context' => $widget_context
	)

**public_pages, walled_garden**
	Filter the URLs that are can be seen by logged out users if Walled Garden is
	enabled. ``$value`` is an array of regex strings that will allow access if matched.

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
	Use this hook to influence the available group tool options

HTMLawed
--------

**allowed_styles, htmlawed**
	Filter the HTMLawed allowed style array.

**config, htmlawed**
	Filter the HTMLawed config array.

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

Twitter API
-----------

**authorize, twitter_api**
	Triggered when a user is authorizes Twitter for a login. ``$params['token']`` contains the Twitter
	authorization token.

Reported Content
----------------

**reportedcontent:add, system**
	Triggered after adding the reported content object ``$params['report']``. Return false to delete report.

**reportedcontent:archive, system**
	Triggered before archiving the reported content object ``$params['report']``. Return false to prevent archiving.

**reportedcontent:delete, system**
	Triggered before deleting the reported content object ``$params['report']``. Return false to prevent deleting.

Search
------

**search, <type>:<subtype>**
	Filter more granular search results than searching by type alone. Must return an array with ``count`` as the
	total count of results and  ``entities`` an array of ElggUser entities.

**search, tags**

**search, <type>**
	Filter the search for entities for type ``$type``. Must return an array with ``count`` as the
	total count of results and  ``entities`` an array of ElggUser entities.

**search_types, get_types**
	Filter an array of search types. This allows plugins to add custom types that don't correspond
	directly to entities.

**search_types, get_queries**
    Before a search this filters the types queried. This can be used to reorder
    the display of search results.

Web Services
------------

**rest, init**
	Triggered by the web services rest handler. Plugins can set up their own authentication
	handlers, then return ``true`` to prevent the default handlers from being registered.

**rest:output, <method_name>**
	Filter the result (and subsequently the output) of the API method
