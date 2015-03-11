Plugin settings
===============

You need to perform some extra steps if your plugin needs settings to be saved and controlled via the administration panel:

- Create a file in your plugin’s default view folder called ``plugins/your_plugin/settings.php``, where ``your_plugin`` is the name of your plugin’s directory in the ``mod`` hierarchy
- Fill this file with the form elements you want to display together with :doc:`internationalised <i18n>` text labels
- Set the name attribute in your form components to ``param[`varname`]`` where ``varname`` is the name of the variable. These will be saved as private settings attached to a plugin entity. So, if your variable is called ``param[myparameter]`` your plugin (which is also passed to this view as ``$vars['entity']``) will be called ``$vars['entity']->myparameter``

An example ``settings.php`` would look like:

.. code:: php

   <p>
      <?php echo elgg_echo('myplugin:settings:limit'); ?>
 
      <select name="params[limit]">
         <option value="5" <?php if ($vars['entity']->limit == 5) echo " selected=\"yes\" "; ?>>5</option>
         <option value="8" <?php if ((!$vars['entity']->limit) || ($vars['entity']->limit == 8)) echo " selected=\"yes\" "; ?>>8</option>
         <option value="12" <?php if ($vars['entity']->limit == 12) echo " selected=\"yes\" "; ?>>12</option>
         <option value="15" <?php if ($vars['entity']->limit == 15) echo " selected=\"yes\" "; ?>>15</option>
      </select>
   </p>

.. note::

   You don’t need to add a save button or the form, this will be handled by the framework.

.. note::

   You cannot use form components that send no value when "off." These include radio inputs and check boxes.

User settings
-------------

Your plugin might need to store per user settings too, and you would like to have your plugin's options to appear in the user's settings page. This is also easy to do and follows the same pattern as setting up the global plugin configuration explained earlier. The only difference is that instead of using a ``settings`` file you will use ``usersettings``. So, the path to the user edit view for your plugin would be ``plugins/your_plugin/usersettings.php``.

.. note::

   The title of the usersettings form will default to the plugin name. If you want to change this, add a translation for ``plugin_id:usersettings:title``.

Retrieving settings in your code
--------------------------------

To retrieve settings from your code use:

.. code:: php

   $setting = elgg_get_plugin_setting($name, $plugin_id);
   
or for user settings

.. code:: php

   $user_setting = elgg_get_plugin_user_setting($name, $user_guid, $plugin_id);
   
where:

- ``$name`` Is the value you want to retrieve
- ``$user_guid`` Is the user you want to retrieve these for (defaults to the currently logged in user)
- ``$plugin_name`` Is the name of the plugin (detected if run from within a plugin)

Setting values while in code
----------------------------

Values may also be set from within your plugin code, to do this use one of the following functions:

.. code:: php

   elgg_set_plugin_setting($name, $value, $plugin_id);

or 

.. code:: php

   elgg_set_plugin_user_setting($name, $value, $user_guid, $plugin_id);
   
.. warning::

   The ``$plugin_id`` needs to be provided when setting plugin (user)settings.