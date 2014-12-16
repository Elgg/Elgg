Events and Plugin Hooks
#######################

.. contents:: Contents
   :local:
   :depth: 2

Overview
========

Elgg has an event system that can be used to replace or extend core
functionality.

Plugins influence the system by creating handlers (`callables <http://php.net/manual/en/language.types.callable.php>`_
such as functions and methods) and registering them to handle
two types of events: `Elgg Events`_ and `Plugin Hooks`_.

When an event is triggered, a set of handlers is executed in order
of priority. Each handler is passed arguments
and has a chance to influence the process. After execution, the "trigger"
function returns a value based on the behavior of the handlers.

Elgg Events vs. Plugin Hooks
----------------------------

The main differences between `Elgg Events`_ and `Plugin Hooks`_ are:

#. Most Elgg events can be cancelled; unless the event is an "after" event,
   a handler that returns `false` can cancel the event, and no more handlers
   are called.
#. Plugin hooks cannot be cancelled; all handlers are always called.
#. Plugin hooks pass an arbitrary value through the handlers, giving each
   a chance to alter along the way.


Elgg Events
===========

Elgg Events are triggered when an Elgg object is created, updated, or
deleted; and at important milestones while the Elgg framework is
loading. Examples: a blog post being created or a user logging in.

Unlike `Plugin Hooks`_, *most Elgg events can be cancelled*, halting the
execution of the handlers, and possibly cancelling an some
action in the Elgg core.

Each Elgg event has a name and an object type (system, user, object,
relationship name, annotation, group) describing the type of object
passed to the handlers.

Before and After Events
-----------------------

Some events are split into "before" and "after". This avoids confusion
around the state of the system while in flux. E.g. Is the user
logged in during the [login, user] event?

Before Events have names ending in ":before" and are triggered before
something happens. Like traditional events, handlers can cancel the
event by returning `false`.

After Events, with names ending in ":after", are triggered after
something happens. Unlike traditional events, handlers *cannot* cancel
these events; all handlers will always be called.

Where before and after events are available, developers are encouraged
to transition to them, though older events will be supported for
backwards compatibility.

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

**Object** here does not refer to an ``ElggObject`` but rather a string describing any object
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

For events with ambiguous states, like logging in a user, you should use `Before and After Events`_
by calling ``elgg_trigger_before_event`` or ``elgg_trigger_after_event``.
This makes it clear for the event handler what state to expect and which events can be cancelled.

.. code:: php

	// handlers for the user, login:before event know the user isn't logged in yet.
	if (!elgg_trigger_before_event('login', 'user', $user)) {
		return false;
	}

	// handlers for the user, login:after event know the user is logged in.
	elgg_trigger_after_event('login', 'user', $user);

Parameters:

-  **$event** The event name.
-  **$object_type** The object type (e.g. "user" or "object").
-  **$object** The object (e.g. an instance of ``ElggUser`` or ``ElggGroup``)

The function will return ``false`` if any of the selected handlers returned
``false`` and the event is stoppable, otherwise it will return ``true``.

.. _design/events#plugin-hooks:

Plugin Hooks
============

Plugin Hooks provide a way for plugins to collaboratively determine or alter
a value. For example, to decide whether a user has permission to edit an entity
or to add additional configuration options to a plugin.

A plugin hook has a value passed into the trigger function, and each handler
has an opportunity to alter the value before it's passed to the next handler.
After the last handler has completed, the final value is returned by the
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

.. warning:: The `$params` and `$value` arguments are reversed between the plugin hook handlers and trigger functions!
