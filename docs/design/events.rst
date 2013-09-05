Events and Plugin Hooks
#######################

.. toctree::
   :maxdepth: 2

Overview
========

Elgg has an event system that can be used to replace or extend core
functionality.

Plugins influence the system by creating handlers (
[http://php.net/manual/en/language.types.callable.php callables]
such as functions and methods) and registering them to handle
two types of events: `Elgg Events`_ and `Plugin Hooks`_.

When an event is triggered, a set of handlers is assembled in order
of priority, then each is executed. Each handler is passed arguments
and has a chance to influence the process. Finally the "trigger"
function returns a value based on the behavior of the handlers.

Elgg Events vs. Plugin Hooks
----------------------------

There are a few big differences between `Elgg Events`_ and `Plugin Hooks`_:

#. Elgg events can be cancelled; if a handler returns `false`, no more
   handlers are called.
#. Elgg events return a boolean value. If any handler returned `false`, the
   event is `false`, otherwise `true`.
#. Plugin hooks pass an arbitrary value through the handlers, giving each
   a chance to alter along the way.
#. Plugin hooks cannot be cancelled; all handlers are always called.

Note: Plugin hooks also allow passing a parameters array to the handlers,
though this will eventually come to Elgg events as well.

Elgg Events
===========

Elgg Events are triggered when an Elgg object is created, updated, or
deleted; and at important milestones while the Elgg framework is
loading. Examples: a blog post being created or a user logging in.

Unlike `Plugin Hooks`_, *Elgg events can be cancelled*, halting the
execution of the handlers, and possibly cancelling or reverting some
action in the Elgg core.

Each Elgg event has a name and an object type (system, user, object,
relationship name, annotation, group) describing the type of object
passed to the handlers.

Elgg Event Handlers
-------------------

Elgg event handlers should have the following prototype:

.. code:: php

    /**
     * @param string $event       The name of the event
     * @param string $object_type The type of $object (e.g. "user", "group")
     * @param mixed  $object      The object of the event
     *
     * @return bool if false, the handler is requesting to cancel the event
     */
    function event_handler($event, $object_type, $object) {
        ...
    }

If the handler returns `false`, the event is cancelled, preventing
execution of the other handlers. All other return values are ignored.

Register to handle an Elgg Event
--------------------------------

Register your handler to an event using ``elgg_register_event_handler``:

.. code:: php

    elgg_register_event_handler($event, $object_type, $handler, $priority);

Parameters:

-  **$event** The event name.
-  **$object_type** The object type (e.g. "user" or "object") or 'all' for
   all types on which the event is fired.
-  **$handler** The callback of the handler function.
-  **$priority** The priority - 0 is first and the default is 500.

**Object** here does not refer to an ``ElggObject`` but rather any object
in the framework: system, user, object, relationship, annotation, group.

Example:

.. code:: php

    // Register the function myPlugin_handle_login() to handle the
    // user login event with priority 400.
    elgg_register_event_handler('login', 'user', 'myPlugin_handle_login', 400);


Trigger an Elgg Event
---------------------

You can trigger a custom Elgg event using ``elgg_trigger_event``:

.. code:: php

    if (elgg_trigger_event($event, $object_type, $object)) {
        // Proceed with doing something.
    } else {
        // Event was cancelled. Roll back any progress made before the event.
    }

Parameters:

-  **$event** The event name.
-  **$object_type** The object type (e.g. "user" or "object").
-  **$object** The object (e.g. an instance of ``ElggUser`` or ``ElggGroup``)

The function will return ``false`` if any of the selected handlers returned
``false``, otherwise it will return ``true``.


Special Events
--------------

TODO

Plugin Hooks
============

Plugin Hooks provide a way for plugins to collaboratively determine or alter
a value. E.g. this could be to set a configuration array or to decide whether
a user has permission to edit an entity.

A plugin hook has a value passed into the trigger function, and each handler
has an opportunity to alter the value before it's passed to the next handler.
After the last handler has completed, the current value is returned by the
trigger.

Plugin Hook Handlers
--------------------

Plugin hook handlers should have the following prototype:

.. code:: php

    /**
     * @param string $hook    The name of the plugin hook
     * @param string $type    The type of the plugin hook
     * @param mixed  $value   The current value of the plugin hook
     * @param mixed  $params  Data passed from the trigger
     *
     * @return mixed if not null, this will be the new value of the plugin hook
     */
    function plugin_hook_handler($hook, $type, $value, $params) {
        ...
    }

If the handler returns no value (or `null` explicitly), the plugin hook value
is not altered. Otherwise the return value becomes the new value of the plugin
hook. It will then be passed to the next handler as `$value`.

Register to handle a Plugin Hook
--------------------------------

Register your handler to a plugin hook using ``elgg_register_plugin_hook_handler``:

.. code:: php

    elgg_register_plugin_hook_handler($hook, $type, $handler, $priority);

Parameters:

-  **$hook** The name of the plugin hook.
-  **$type** The type of the hook or 'all' for all types.
-  **$handler** The callback of the handler function.
-  **$priority** The priority - 0 is first and the default is 500.

**Type** can vary in meaning. It may mean an Elgg entity type or something
specific to the plugin hook name.

Example:

.. code:: php

    // Register the function myPlugin_hourly_job() to be called with priority 400.
    elgg_register_plugin_hook_handler('cron', 'hourly', 'myPlugin_hourly_job', 400);


Trigger a Plugin Hook
---------------------

You can trigger a custom plugin hook using ``elgg_trigger_plugin_hook``:

.. code:: php

    // filter $value through the handlers
    $value = elgg_trigger_plugin_hook($hook, $type, $params, $value);

Parameters:

-  **$hook** The name of the plugin hook.
-  **$type** The type of the hook or 'all' for all types.
-  **$params** Arbitrary data passed from the trigger to the handlers.
-  **$value** The initial value of the plugin hook.

**Caveat!** The `$params` and `$value` arguments are reversed between the
plugin hook handlers and trigger functions!
