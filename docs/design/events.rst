Events
######

.. contents:: Contents
   :local:
   :depth: 2

Overview
========

Elgg has an event system that can be used to replace or extend core functionality.

Plugins influence the system by creating handlers (`callables <http://php.net/manual/en/language.types.callable.php>`_
such as functions and methods) and registering them to handle the events.

When an event is triggered, a set of handlers is executed in order of priority. Each handler is passed arguments
and has a chance to influence the process. After execution, the "trigger" function returns a value based on the behavior of the handlers.

.. seealso::

	- :doc:`/guides/events-list`

Elgg Events
===========

Elgg Events are triggered when an Elgg object is created, updated, or
deleted; and at important milestones while the Elgg framework is
loading. Examples: a blog post being created or a user logging in.

These events are mostly used to notify the rest of the system that something has happened.

There are also events that are used to influence output, configuration or behaviour of the system.

Each Elgg event has a name and a type (system, user, object, relationship name, annotation, group) 
describing the type of object passed to the handlers.

.. _before-after:

Before and After Events
-----------------------

Some events are split into "before" and "after". This avoids confusion
around the state of the system while in flux. E.g. Is the user
logged in during the [login, user] event?

Before Events have names ending in ":before" and are triggered before
something happens. Handlers can cancel the event by returning ``false``.
When ``false`` is returned by a handler, following handlers will not be called.

After Events, with names ending in ":after", are triggered after
something happened. Handlers *cannot* cancel these events; all handlers will always be called.

Where before and after events are available, developers are encouraged
to transition to them, though older events will be supported for
backwards compatibility.

Elgg Event Handlers
-------------------

Elgg event handlers are callables:

.. code-block:: php

    <?php

    /**
     * @param \Elgg\Event $event The event object
     *
     * @return bool if false, the handler is requesting to cancel the event
     */
    function event_handler(\Elgg\Event $event) {
        ...
    }

In ``event_handler``, the ``Event`` object has various methods for getting the name, object type,
and object of the event. See the ``Elgg\Event`` class for details.

Register to handle an Elgg Event
--------------------------------

Register your handler to an event using ``elgg_register_event_handler``:

.. code-block:: php

    <?php

    elgg_register_event_handler($event, $type, $handler, $priority);

Parameters:

-  **$event** The event name.
-  **$type** The event type (e.g. "user" or "object") or 'all' for all types on which the event is fired.
-  **$handler** The callback of the handler function.
-  **$priority** The priority - 0 is first and the default is 500.

Example:

.. code-block:: php

    <?php

    // Register the function myPlugin_handle_create_object() to handle the
    // create object event with priority 400.
    elgg_register_event_handler('create:after', 'object', 'myPlugin_handle_create_object', 400);

.. warning::

   If you handle the "update" event on an object, avoid calling ``save()`` in your event handler. For one it's
   probably not necessary as the object is saved after the event completes, but also because ``save()`` calls
   another "update" event and makes ``$object->getOriginalAttributes()`` no longer available.

Invokable classes as handlers
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

You may use a class with an ``__invoke()`` method as a handler. Just register the class name and it will be instantiated (with no arguments) 
for the lifetime of the event.

.. code-block:: php

    <?php

    namespace MyPlugin;

    class UpdateObjectHandler {
        public function __invoke(\Elgg\Event $event) {

        }
    }

    // in init, system
    elgg_register_event_handler('update', 'object', MyPlugin\UpdateObjectHandler::class);


Trigger an Elgg Event
---------------------

You can trigger a custom Elgg event using ``elgg_trigger_event``:

.. code-block:: php

    <?php

    if (elgg_trigger_event($event, $object_type, $object)) {
        // Proceed with doing something.
    } else {
        // Event was cancelled. Roll back any progress made before the event.
    }

For events with ambiguous states, like logging in a user, you should use :ref:`before-after`
by calling ``elgg_trigger_before_event`` or ``elgg_trigger_after_event``.
This makes it clear for the event handler what state to expect and which events can be cancelled.

.. code-block:: php

    <?php

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

.. _design/events#event-sequence:

Trigger an Event with results
-----------------------------

Events with results provide a way for plugins to collaboratively determine or alter
a value. For example, to decide whether a user has permission to edit an entity
or to add additional configuration options to a plugin.

An event has a value passed into the trigger function, and each handler
has an opportunity to alter the value before it's passed to the next handler.
After the last handler has completed, the final value is returned by the
trigger.

You can trigger a custom event using ``elgg_trigger_event_results``:

.. code-block:: php

    <?php

    // filter $value through the handlers
    $value = elgg_trigger_event_results($name, $type, $params, $value);

Parameters:

-  **$name** The name of the event.
-  **$type** The type of the event or 'all' for all types.
-  **$params** Arbitrary data passed from the trigger to the handlers.
-  **$value** The initial value of the event.

Trigger an Elgg Event sequence
------------------------------

Instead of triggering the ``:before`` and ``:after`` event manually, it's possible to trigger an event sequence. This will trigger 
the ``:before`` event, then the actual event and finally the ``:after`` event.

.. code-block:: php

	elgg()->events->triggerSequence($event, $type, $object, $callable);

When called with for example ``'cache:clear', 'system'`` the following three events are triggered

- ``'cache:clear:before', 'system'``
- ``'cache:clear', 'system'``
- ``'cache:clear:after', 'system'``
	
Parameters:

- **$event** The event name.
- **$object_type** The object type (e.g. "user" or "object").
- **$object** The object (e.g. an instance of ``ElggUser`` or ``ElggGroup``)
- **$callable** Callable to run on successful event, before event:after

Unregister Event Handlers
-------------------------

The functions ``elgg_unregister_event_handler`` can be used to remove
handlers already registered by another plugin or Elgg core. The parameters are in the same order as the registration
functions, except there's no priority parameter.

.. code-block:: php

    <?php

    elgg_unregister_event_handler('login', 'user', 'myPlugin_handle_login');

Anonymous functions or invokable objects cannot be unregistered, but dynamic method callbacks can be unregistered
by giving the static version of the callback:

.. code-block:: php

    <?php

    $obj = new MyPlugin\Handlers();
    elgg_register_event_handler('foo', 'bar', [$obj, 'handleFoo']);

    // ... elsewhere

    elgg_unregister_event_handler('foo', 'bar', 'MyPlugin\Handlers::handleFoo');

Even though the event handler references a dynamic method call, the code above will successfully
remove the handler.

Handler Calling Order
---------------------

Handlers are called first in order of priority, then registration order.

.. note::

    Before Elgg 2.0, registering with the ``all`` keywords caused handlers to be called later, even
    if they were registered with lower priorities.
