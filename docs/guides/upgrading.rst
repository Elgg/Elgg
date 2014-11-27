Upgrading Plugins
#################

Prepare your plugin for the next version of Elgg.

See the administator guides for :doc:`how to upgrade a live site </admin/upgrading>`.

.. contents:: Contents
   :local:
   :depth: 2

From 1.8 to 1.9
===============

In the examples we are upgrading an imaginary "Photos" plugin.

Only the key changes are included. For example some of the deprecated functions are not mentioned here separately.

Each section will include information whether the change is backwards compatible with Elgg 1.8.

The manifest file
-----------------

No changes are needed if your plugin is compatible with 1.8.

It's however recommended to add the ``<id>`` tag. It's value should be the name of the directory where the plugin is located inside the ``mod/`` directory.

If you make changes that break BC, you must update the plugin version and the required Elgg release.

Example of (shortened) old version:

.. code:: xml

    <?xml version="1.0" encoding="UTF-8"?>
    <plugin_manifest xmlns="http://www.elgg.org/plugin_manifest/1.8">
        <name>Photos</name>
        <author>John Doe</author>
        <version>1.0</version>
        <description>Adds possibility to upload photos and arrange them into albums.</description>
        <requires>
            <type>elgg_release</type>
            <version>1.8</version>
        </requires>
    </plugin_manifest>

Example of (shortened) new version:

.. code:: xml

    <?xml version="1.0" encoding="UTF-8"?>
    <plugin_manifest xmlns="http://www.elgg.org/plugin_manifest/1.8">
        <name>Photos</name>
        <id>photos</id>
        <author>John Doe</author>
        <version>2.0</version>
        <description>Adds possibility to upload photos and arrange them into albums.</description>
        <requires>
            <type>elgg_release</type>
            <version>1.9</version>
        </requires>
    </plugin_manifest>

$CONFIG and $vars['config']
---------------------------

Both the global ``$CONFIG`` variable and the ``$vars['config']`` parameter have been deprecated. They should be replaced with the ``elgg_get_config()`` function.

Example of old code:

.. code:: php

    // Using the global $CONFIG variable:
    global $CONFIG;
    $plugins_path = $CONFIG->plugins_path

    // Using the $vars view parameter:
    $plugins_path = $vars['plugins_path'];

Example of new code:

.. code:: php

    $plugins_path = elgg_get_config('plugins_path');

.. note::

    Compatible with 1.8

.. note::

    See how the community_plugins plugin was updated: https://github.com/Elgg/community_plugins/commit/f233999bbd1478a200ee783679c2e2897c9a0483

Language files
--------------

In Elgg 1.8 the language files needed to use the ``add_translation()`` function. In 1.9 it is enough to just return the array that was
previously passed to the function as a parameter. Elgg core will use the file name (e.g. en.php) to tell which language the file contains.

Example of the old way in ``languages/en.php``:

.. code:: php

    $english = array(
        'photos:all' => 'All photos',
    );
    add_translation('en', $english);

Example of new way:

.. code:: php

    return array(
        'photos:all' => 'All photos',
    );

.. warning::

    Not compatible with 1.8

Notifications
-------------

One of the biggest changes in Elgg 1.9 is the notifications system. The new system allows more flexible and scalable way of sending notifications.

Example of the old way:

.. code:: php

    function photos_init() {
        // Tell core that we want to send notifications about new photos
        register_notification_object('object', 'photo', elgg_echo('photo:new'));

        // Register a handler that creates the notification message
        elgg_register_plugin_hook_handler('notify:entity:message', 'object', 'photos_notify_message');
    }

    /**
     * Set the notification message body
     *
     * @param string $hook    Hook name
     * @param string $type    Hook type
     * @param string $message The current message body
     * @param array  $params  Parameters about the photo
     * @return string
     */
    function photos_notify_message($hook, $type, $message, $params) {
        $entity = $params['entity'];
        $to_entity = $params['to_entity'];
        $method = $params['method'];
        if (elgg_instanceof($entity, 'object', 'photo')) {
            $descr = $entity->excerpt;
            $title = $entity->title;
            $owner = $entity->getOwnerEntity();
            return elgg_echo('photos:notification', array(
                $owner->name,
                $title,
                $descr,
                $entity->getURL()
            ));
        }
        return null;
    }

Example of the new way:

.. code:: php

    function photos_init() {
        elgg_register_notification_event('object', 'photo', array('create'));
        elgg_register_plugin_hook_handler('prepare', 'notification:publish:object:photo', 'photos_prepare_notification');
    }

    /**
     * Prepare a notification message about a new photo
     *
     * @param string                          $hook         Hook name
     * @param string                          $type         Hook type
     * @param Elgg_Notifications_Notification $notification The notification to prepare
     * @param array                           $params       Hook parameters
     * @return Elgg_Notifications_Notification
     */
    function photos_prepare_notification($hook, $type, $notification, $params) {
        $entity = $params['event']->getObject();
        $owner = $params['event']->getActor();
        $recipient = $params['recipient'];
        $language = $params['language'];
        $method = $params['method'];

        // Title for the notification
        $notification->subject = elgg_echo('photos:notify:subject', array($entity->title), $language);

        // Message body for the notification
        $notification->body = elgg_echo('photos:notify:body', array(
            $owner->name,
            $entity->title,
            $entity->getExcerpt(),
            $entity->getURL()
        ), $language);

        // The summary text is used e.g. by the site_notifications plugin
        $notification->summary = elgg_echo('photos:notify:summary', array($entity->title), $language);

        return $notification;
    }

.. warning::

    Not compatible with 1.8

.. note::

    See how the community_plugins plugin was updated to use the new system: https://github.com/Elgg/community_plugins/commit/bfa356cfe8fb99ebbca4109a1b8a1383b70ff123

Notifications can also be sent with the ``notify_user()`` function.

It has however been updated to support three new optional parameters passed inside an array as the fifth parameter.

The parameters give notification plugins more control over the notifications, so they should be included whenever possible. For example the bundled site_notifications plugin won't work properly if the parameters are missing.

Parameters:

-  **object** The object that we are notifying about (e.g. ElggEntity or ElggAnnotation). This is needed so that notification plugins can provide a link to the object.
-  **action** String that describes the action that triggered the notification (e.g. "create", "update", etc).
-  **summary** String that contains a summary of the notification. (It should be more informative than the notification subject but less informative than the notification body.)

Example of the old way:

.. code:: php

	// Notify $owner that $user has added a $rating to an $entity created by him

	$subject = elgg_echo('rating:notify:subject');
	$body = elgg_echo('rating:notify:body', array(
		$owner->name,
		$user->name,
		$entity->title,
		$entity->getURL(),
	));

	notify_user($owner->guid,
				$user->guid,
				$subject,
				$body
			);

Example of the new way:

.. code:: php

	// Notify $owner that $user has added a $rating to an $entity created by him

	$subject = elgg_echo('rating:notify:subject');
	$summary = elgg_echo('rating:notify:summary', array($entity->title));
	$body = elgg_echo('rating:notify:body', array(
		$owner->name,
		$user->name,
		$entity->title,
		$entity->getURL(),
	));

	$params = array(
		'object' => $rating,
		'action' => 'create',
		'summary' => $summary,
	);

	notify_user($owner->guid,
				$user->guid,
				$subject,
				$body,
				$params
			);

.. note::

    Compatible with 1.8

Adding items to the Activity listing
------------------------------------

.. code:: php

    add_to_river('river/object/photo/create', 'create', $user_guid, $photo_guid);

.. code:: php

    elgg_create_river_item(array(
        'view' => 'river/object/photo/create',
        'action_type' => 'create',
        'subject_guid' => $user_guid,
        'object_guid' => $photo_guid,
    ));

You can also add the optional ``target_guid`` parameter which tells the target of the create action.

If the photo would had been added for example into a photo album, we could add it by passing in also:

.. code:: php

    'target_guid' => $album_guid,

.. warning::

    Not compatible with 1.8

Entity URL handlers
-------------------

The ``elgg_register_entity_url_handler()`` function has been deprecated. In 1.9 you should use the ``'entity:url', 'object'`` plugin hook instead.

Example of the old way:

.. code:: php

    /**
     * Initialize the photo plugin
     */
    my_plugin_init() {
        elgg_register_entity_url_handler('object', 'photo', 'photo_url_handler');
    }

    /**
     * Returns the URL from a photo entity
     *
     * @param ElggEntity $entity
     * @return string
     */
    function photo_url_handler($entity) {
    	return "photo/view/{$entity->guid}";
    }

Example of the new way:

.. code:: php

    /**
     * Initialize the photo plugin
     */
    my_plugin_init() {
        elgg_register_plugin_hook_handler('entity:url', 'object', 'photo_url_handler');
    }

    /**
     * Returns the URL from a photo entity
     *
     * @param string $hook   'entity:url'
     * @param string $type   'object'
     * @param string $url    The current URL
     * @param array  $params Hook parameters
     * @return string
     */
    function photo_url_handler($hook, $type, $url, $params) {
        $entity = $params['entity'];

        // Check that the entity is a photo object
        if ($entity->getSubtype() !== 'photo') {
            // This is not a photo object, so there's no need to go further
            return;
        }

        return "photo/view/{$entity->guid}";
    }

.. warning::

    Not compatible with 1.8

Web services
------------

In Elgg 1.8 the web services API was included in core and methods were exposed
using ``expose_function()``. To enable the same functionality for Elgg 1.9,
enable the "Web services 1.9" plugin and replace all calls to
``expose_function()`` with  ``elgg_ws_expose_function()``.

From 1.7 to 1.8
===============
Elgg 1.8 is the biggest leap forward in the development of Elgg since version 1.0.
As such, there is more work to update core and plugins than with previous upgrades.
There were a small number of API changes and following our standard practice,
the methods we deprecated have been updated to work with the new API.
The biggest changes are in the standardization of plugins and in the views system.


Updating core
-------------
Delete the following core directories (same level as _graphics and engine):

* _css
* account
* admin
* dashboard
* entities
* friends
* search
* settings
* simplecache
* views

.. warning::

   If you do not delete these directories before an upgrade, you will have problems!


Updating plugins
----------------

Use standardized routing with page handlers
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
* All: /page_handler/all
* User’s content: /page_handler/owner/:username
* User’s friends' content: /page_handler/friends/:username
* Single entity: /page_handler/view/:guid/:title
* Added: /page_handler/add/:container_guid
* Editing: /page_handler/edit/:guid
* Group list: /page_handler/group/:guid/all


Include page handler scripts from the page handler
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Almost every page handler should have a page handler script.
(Example: ``bookmarks/all => mod/bookmarks/pages/bookmarks/all.php``)

* Call ``set_input()`` for entity guids in the page handler and use ``get_input()`` in the page handler scripts.
* Call ``gatekeeper()`` and ``admin_gatekeeper()`` in the page handler function if required.
* The group URL should use the ``pages/:handler/owner.php`` script.
* Page handlers should not contain HTML.
* Update the URLs throughout the plugin. (Don't forget to remove ``/pg/``!)


Use standardized page handlers and scripts
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
* Store page handler scripts in ``mod/:plugin/pages/:page_handler/:page_name.php``
* Use the content page layout in page handler scripts:

  .. code:: php

     $content = elgg_view_layout('content', $options);

* Page handler scripts should not contain HTML.
* Call ``elgg_push_breadcrumb()`` in the page handler scripts.
* No need to set page owner if the URLs are in the standardized format.
* For group content, check the container_guid by using elgg_get_page_owner_entity().


The ``object/:subtype`` view
~~~~~~~~~~~~~~~~~~~~~~~~~~~~
* Make sure there are views for ``$vars['full_view'] == true`` and ``$vars['full_view'] == false``. ``$vars['full_view']`` replaced ``$vars['full]``.
* Check for the object in ``$vars['entity']``. Use ``elgg_instance_of()`` to make sure it's the type of entity you want.
* Return ``true`` to short circuit the view if the entity is missing or wrong.
* Use ``elgg_view(‘object/elements/summary’, array(‘entity’ => $entity));`` and ``elgg_view_menu(‘entity’, array(‘entity’ => $entity));`` to help format. You should use very little markup in these views.


Update action structure
~~~~~~~~~~~~~~~~~~~~~~~
* Namespace action files and action names (example: ``mod/blog/actions/blog/save.php`` => ``action/blog/save``)
* Use the following action URLs:
  
  * Add: ``action/:plugin/save``
  * Edit: ``action/:plugin/save``
  * Delete: ``action/:plugin/delete``

* Make the delete action accept ``action/:handler/delete?guid=:guid`` so the metadata entity menu has the correct URL by default.


Update deprecated functions
~~~~~~~~~~~~~~~~~~~~~~~~~~~
* Functions deprecated in 1.7 will produce visible errors in 1.8.
  
  * See ``/engine/lib/deprecated-1.7.php`` for the full list.

* You can also update functions deprecated in 1.8.
  
  * Many registration functions simply added an ``elgg_`` prefix for consistency, and should be easy to update.
  * See ``/engine/lib/deprecated-1.8.php`` for the full list.
  * You can set the debug level to “warning” to get visual reminders of deprecated functions.


Update the widget views
~~~~~~~~~~~~~~~~~~~~~~~
See the blog or file widgets for examples.


Update the group profile module
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Use the blog or file plugins for examples. This will help with making your plugin themeable by the new CSS framework.


Update forms
~~~~~~~~~~~~
* Move form bodies to the ``forms/:action`` view to use Evan's new ``elgg_view_form``.
* Use input views in form bodies rather than html. This helps with theming and future-proofing.
* Add a function that prepares the form (see ``mod/file/lib/file.php`` for an example)
* Make your forms sticky (see the file plugin's upload action and form prepare function).

The forms API is discussed in more detail in :doc:`/guides/actions`.


Clean up CSS/HTML
~~~~~~~~~~~~~~~~~
We have added many CSS patterns to the base CSS file (modules, image block, spacing primitives). We encourage you to use these patterns and classes wherever possible. Doing so should:

1. Reduce maintenance costs, since you can delete most custom CSS.
2. Make your plugin more compatible with community themes.

Look for patterns that can be moved into core if you need significant CSS.

We use hyphens rather than underscores in classes/ids and encourage you do the same for consistency.

If you do need your own CSS, you should use your own namespace, rather than ``elgg-``.


Update manifest.xml
~~~~~~~~~~~~~~~~~~~
* Use http://el.gg/manifest17to18 to automate this.
* Don't use the "bundled" category with your plugins. That is only for plugins distributed with Elgg.


Update settings and user settings views
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
* The view for settings is now ``plugins/:plugin/settings`` (previously ``settings/:plugin/edit``).
* The view for user settings is now ``plugins/:plugin/usersettings`` (previously ``usersettings/:plugin/edit``).
