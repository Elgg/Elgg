Context
=======

.. warning::

	The contents of this page are outdated. While the functionality is still in place, using global context to
	determine your business logic is bad practice, and will make your code less testable and succeptive to bugs.


Within the Elgg framework, context can be used by your plugin's functions to determine if they should run or not.
You will be registering callbacks to be executed when particular :doc:`events are triggered <events-list>`.
Sometimes the events are generic and you only want to run your callback when your plugin caused the event to be triggered.
In that case, you can use the page's context.

You can explicitly set the context with ``set_context()``. The context is a string and typically you set it to the name of your plugin.
You can retrieve the context with the function ``get_context()``.
It's however better to use ``elgg_push_context($string)`` to add a context to the stack.
You can check if the context you want in in the current stack by calling ``elgg_in_context($context)``.
Don't forget to pop (with ``elgg_pop_context()``) the context after you push one and don't need it anymore.

If you don't set it, Elgg tries to guess the context. If the page was called through the router,
the context is set to the first segment of the current route, e.g. ``profile`` in ``profile/username``.

Sometimes a view will return different HTML depending on the context.
A plugin can take advantage of that by setting the context before calling ``elgg_view()`` on the view and then setting the context back.
This is frequently done with the search context.