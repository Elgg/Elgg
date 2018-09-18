Widgets
=======

Widgets are content areas that users can drag around their page to customize the layout. They can typically be customized by their owner to show more/less content and determine who sees the widget. By default Elgg provides plugins for customizing the profile page and dashboard via widgets.

.. contents:: Contents
   :local:
   :depth: 2

Structure
---------

To create a widget, create two views:

* ``widgets/widget/edit``
* ``widgets/widget/content``

``content.php`` is responsible for all the content that will output within the widget. The ``edit.php`` file contains any extra edit functions you wish to present to the user. You do not need to add access level as this comes as part of the widget framework.

.. note::
   
   Using HTML checkboxes to set widget flags is problematic because if unchecked,
   the checkbox input is omitted from form submission.
   The effect is that you can only set and not clear flags.
   The "input/checkboxes" view will not work properly in a widget's edit panel.

Register the widget
-------------------

Once you have created your edit and view pages, you need to initialize the plugin widget.

The easiest way to do this is to add the ``widgets`` section to your ``elgg-plugin.php`` config file.

.. code-block:: php

	return [
		'widgets' => [
			'filerepo' => [
				'context' => ['profile'],
			],
		]
	];
	
Alternatively you can also use an function to add a widget. This is done within the plugins ``init()`` function.

.. code-block:: php

    // Add generic new file widget
    elgg_register_widget_type([
        'id' => 'filerepo', 
        'name' => elgg_echo('widgets:filerepo:name'), 
        'description' => elgg_echo('widgets:filerepo:description'),
        'context' => ['profile'],
    ]);

.. note::

    The only required attribute is the ``id``.

Multiple widgets
^^^^^^^^^^^^^^^^

It is possible to add multiple widgets for a plugin. You just initialize as many widget directories as you need.

.. code-block:: php

    // Add generic new file widget
    elgg_register_widget_type([
        'id' => 'filerepo', 
        'name' => elgg_echo('widgets:filerepo:name'), 
        'description' => elgg_echo('widgets:filerepo:description'),
        'context' => ['profile'],
    ]);

    // Add a second file widget
    elgg_register_widget_type([
        'id' => 'filerepo2', 
        'name' => elgg_echo('widgets:filerepo2:name'), 
        'description' => elgg_echo('widgets:filerepo2:description'),
        'context' => ['dashboard'],
    ]);

    // Add a third file widget
    elgg_register_widget_type([
        'id' => 'filerepo3', 
        'name' => elgg_echo('widgets:filerepo3:name'), 
        'description' => elgg_echo('widgets:filerepo3:description'),
        'context' => ['profile', 'dashboard'],
    ]);

Make sure you have the corresponding directories within your plugin
views structure:

.. code-block:: text

    'Plugin'
        /views
            /default
                /widgets
                   /filerepo
                      /edit.php
                      /content.php
                   /filerepo2
                      /edit.php
                      /content.php
                   /filerepo3
                      /edit.php
                      /content.php

Magic widget name and description
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
When registering a widget you can omit providing a name and a description. If a translation in the following format is provided, they will be used. For the name: ``widgets:<widget_id>:name`` and for the description ``widgets:<widget_id>:description``. If you make sure these translation are available in a translation file, you have very little work registering the widget.

.. code-block:: php

    elgg_register_widget_type(['id' => 'filerepo']);

How to restrict where widgets can be used
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
The widget can specify the context that it can be used in (just profile, just dashboard, etc.).

.. code-block:: php

    elgg_register_widget_type([
        'id' => 'filerepo',
        'context' => ['profile', 'dashboard', 'other_context'],
    ]);

Allow multiple widgets on the same page
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
By default you can only add one widget of the same type on the page. If you want more of the same widget on the page, you can specify this when registering the widget:

.. code-block:: php

    elgg_register_widget_type([
        'id' => 'filerepo',
        'multiple' => true,
    ]);


Register widgets in a hook
^^^^^^^^^^^^^^^^^^^^^^^^^^
If, for example, you wish to conditionally register widgets you can also use a hook to register widgets.

.. code-block:: php

    function my_plugin_init() {
        elgg_register_plugin_hook_handler('handlers', 'widgets', 'my_plugin_conditional_widgets_hook');
    }

    function my_plugin_conditional_widgets_hook($hook, $type, $return, $params) {
        if (!elgg_is_active_plugin('file')) {
            return;
        }

        $return[] = \Elgg\WidgetDefinition::factory([
            'id' => 'filerepo',
        ]);

        return $return;
    }

Modify widget properties of existing widget registration
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
If, for example, you wish to change the allowed contexts of an already registered widget you can do so by re-registering the widget with ``elgg_register_widget_type`` as it will override an already existing widget definition. If you want even more control you can also use the ``handlers, widgets`` hook to change the widget definition.

.. code-block:: php

    function my_plugin_init() {
        elgg_register_plugin_hook_handler('handlers', 'widgets', 'my_plugin_change_widget_definition_hook');
    }

    function my_plugin_change_widget_definition_hook($hook, $type, $return, $params) {
        foreach ($return as $key => $widget) {
            if ($widget->id === 'filerepo') {
                $return[$key]->multiple = false;
            }
        }

        return $return;
    }

Default widgets
---------------

If your plugin uses the widget canvas, you can register default widget support with Elgg core, which will handle everything else.

To announce default widget support in your plugin, register for the ``get_list, default_widgets`` plugin hook:

.. code-block:: php

    elgg_register_plugin_hook_handler('get_list', 'default_widgets', 'my_plugin_default_widgets_hook');

In the plugin hook handler, push an array into the return value defining your default widget support and when to create default widgets. Arrays require the following keys to be defined:

-  name - The name of the widgets page. This is displayed on the tab in the admin interface.
-  widget\_context - The context the widgets page is called from. (If not explicitly set, this is your plugin's id.)
-  widget\_columns - How many columns the widgets page will use.
-  event - The Elgg event to create new widgets for. This is usually ``create``.
-  entity\_type - The entity type to create new widgets for.
-  entity\_subtype - The entity subtype to create new widgets for. The can be ELGG\_ENTITIES\_ANY\_VALUE to create for all entity types.

When an object triggers an event that matches the event, entity\_type, and entity\_subtype parameters passed, Elgg core will look for default widgets that match the widget\_context and will copy them to that object's owner\_guid and container\_guid. All widget settings will also be copied.

.. code-block:: php

    function my_plugin_default_widgets_hook($hook, $type, $return, $params) {
        $return[] = array(
            'name' => elgg_echo('my_plugin'),
            'widget_context' => 'my_plugin',
            'widget_columns' => 3,

            'event' => 'create',
            'entity_type' => 'user',
            'entity_subtype' => ELGG_ENTITIES_ANY_VALUE,
        );

        return $return;
    }
