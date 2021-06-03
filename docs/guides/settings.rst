Plugin settings
===============

.. contents:: Contents
   :local:
   :depth: 1

You need to perform some extra steps if your plugin needs settings to be saved and controlled via the administration panel:

- Create a file in your plugin’s default view folder called ``plugins/your_plugin/settings.php``, where ``your_plugin`` is the 
  name of your plugin’s directory in the ``mod`` hierarchy
- Fill this file with the form elements you want to display together with :doc:`internationalised <i18n>` text labels
- Set the name attribute in your form components to ``params[`varname`]`` where ``varname`` is the name of the variable. These will be 
  saved as private settings attached to a plugin entity. So, if your variable is called ``params[myparameter]`` your plugin (which is also 
  passed to this view as ``$vars['entity']``) will be called ``$vars['entity']->myparameter``

An example ``settings.php`` would look like:

.. code-block:: php

	echo elgg_view_field([
		'#type' => 'select',
		'#label' => elgg_echo('myplugin:settings:limit'),
		'name' => 'params[limit]',
		'value' => $vars['entity']->limit,
		'options' => [5,8,12,15],
	]);

.. note::

   You don’t need to add a save button or the form, this will be handled by the framework.

.. note::

   You cannot use form components that send no value when "off." These include radio inputs and check boxes.

If your plugin settings require a cache flush you can add a (hidden) input on the form with the name 'flush_cache' and value '1'
  
.. code-block:: php

	elgg_view_field([
		'#type' => 'hidden',
		'name' => 'flush_cache',
		'value' => 1,
	]);

User settings
-------------

Your plugin might need to store per user settings too, and you would like to have your plugin's options to appear in the user's settings page. 
This is also easy to do and follows the same pattern as setting up the global plugin configuration explained earlier. The only difference is 
that instead of using a ``settings`` file you will use ``usersettings``. So, the path to the user edit view for your plugin would be 
``plugins/<your_plugin>/usersettings.php``.

.. note::

   The title of the usersettings form will default to the plugin name. If you want to change this, add a translation for ``<plugin_id>:usersettings:title``.

Group settings
--------------

If your plugin needs settings per group you can extend the view ``groups/edit/settings`` to show your settings. The settings are shown during 
group creation and edit. In order for the settings to be saved correctly they need a name in the format ``settings[<plugin id>][<setting name>]``.

Retrieving settings in your code
--------------------------------

To retrieve settings from your code use:

.. code-block:: php

   $setting = elgg_get_plugin_setting($name, $plugin_id);
   
or for user settings:

.. code-block:: php

   $user_setting = elgg_get_plugin_user_setting($name, $user_guid, $plugin_id);
   
   // or
   $user = get_user($user_guid);
   $user_setting = $user->getPluginSetting($plugin_id, $name);
   
where:

- ``$name`` Is the value you want to retrieve
- ``$user_guid`` Is the user you want to retrieve these for (defaults to the currently logged in user)
- ``$plugin_name`` Is the name of the plugin (detected if run from within a plugin)

or for group settings:

.. code-block:: php

	$group = get_entity($group_guid);
	$value = $group->getPluginSetting('<plugin id>', '<setting name>');

Setting values while in code
----------------------------

Values may also be set from within your plugin code, to do this use one of the following functions:

.. code-block:: php

   $plugin = elgg_get_plugin_from_id($plugin_id);
   $plugin->setSetting($name, $value);

or for user settings:

.. code-block:: php

   $user = elgg_get_logged_in_user_entity();
   $user->setPluginSetting($plugin_id, $name, $value);

or for group settings:

.. code-block:: php

	$group = get_entity($group_guid);
	$group->setPluginSetting($plugin_id, $name, $value);

.. warning::

   The ``$plugin_id`` needs to be provided when setting plugin (user)settings.

.. warning::

	Since plugin settings are saved as private settings only `scalar <https://www.php.net/manual/en/function.is-scalar.php>`_ values 
	are allowed, so no objects or arrays.

Default plugin (group|user) settings
------------------------------------

If a plugin or a user not have a setting stored in the database, you sometimes have the need for a certain default value.
You can pass this when using the getter functions.

.. code-block:: php

   $user_setting = elgg_get_plugin_user_setting($name, $user_guid, $plugin_id, $default);
   
   $plugin_setting = elgg_get_plugin_setting($name, $plugin_id, $default);
   
   $group_setting = $group->getPluginSetting($plugin_id, $name, $default);
   
Alternatively you can also provide default plugin and user settings in the ``elgg-plugin.php`` file.

.. code-block:: php

	<?php

	return [
		'settings' => [
		    'key' => 'value',
		],
		'user_settings' => [
		    'key' => 'value',
		],
	];

.. note::

	Group settings don't have a default value available in the ``elgg-plugin.php`` file.
