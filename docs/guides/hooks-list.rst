List of plugin hooks in core
############################

.. contents:: Contents
   :local:
   :depth: 1

System hooks
============

**email, system**
	Triggered when sending email. ``$params`` contains:

	* to
	* from
	* subject
	* body
	* headers
	* params


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

**output, ajax**
	Triggered in the ajax forward hook that is called for ajax requests. Allows plugins to alter the
	output returned, including the forward URL, system messages, and errors.

**parameters, menu:<menu_name>**
	Triggered by ``elgg_view_menu()``. Used to change menu variables (like sort order) before it is generated.

**register, menu:<menu_name>**
	Triggered by ``elgg_view_menu()``. Used to add dynamic menu items.

**prepare, menu:<menu_name>**
	Trigger by ``elgg_view_menu()``. Used to sort, add, remove, and modify menu items.

**creating, river**
	Triggered before a river item is created. Return false to prevent river item from being created.

**simplecache:generate, <view>**
	Triggered when generating the cached content of a view.

**get, subscriptions**
	Filter notification subscriptions for users for the Elgg_Notifications_Event ``$params['event']``.
	Return an array like:

.. code:: php

	array(
		<user guid> => array('subscription', 'types'),
		<user_guid2> => array('email', 'sms', 'ajax')
	);

**prepare, breadcrumbs**
    In elgg_get_breadcrumbs(), this filters the registered breadcrumbs before
    returning them, allowing a plugin to alter breadcrumb strategy site-wide.

**add, river**

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

.. _guides/hooks-list#permission-hooks:

Permission hooks
================

**container_permissions_check, <entity_type>**
	Return boolean for if the user ``$params['user']`` can use the entity ``$params['container']``
	as a container for an entity of ``<entity_type>`` and subtype ``$params['subtype']``.

**permissions_check, <entity_type>**
	Return boolean for if the user ``$params['user']`` can edit the entity ``$params['entity']``.

**permissions_check:delete, <entity_type>**
	Return boolean for if the user ``$params['user']`` can delete the entity ``$params['entity']``. Defaults to ``$entity->canEdit()``.

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
		$config = elgg_get_config('icon_sizes');
		$key = $params['size'];
		if (isset($config[$key])) {
			$size = $config[$key]['w'] . 'x' . $config[$key]['h'];
		}
		
		// Produce URL used to retrieve icon
		return "http://www.gravatar.com/avatar/$hash?s=$size";
	}

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

**rest, init**
	Triggered by the web services rest handler. Plugins can set up their own authentication
	handlers, then return true to prevent the default handlers from being registered.

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

HTMLawed
--------

**allowed_styles, htmlawed**
	Filter the HTMLawed allowed style array.

**config, htmlawed**
	Filter the HTMLawed config array.

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
