Group Tools
===========

Elgg groups allow group administrators to enable/disable various tools available within a group.
These tools are provided by other plugins like blog or file.

Plugins can access group tool register via ``elgg()->group_tools``.

.. code-block:: php

	elgg()->group_tools->register('my-tool', [
		'default_on' => false, // default is true
		'label' => elgg_echo('my-tool:checkbox:label'),
		'priority' => 300, // display this earlier than other modules/tools
	]);

A registered tool will have an option to be toggled on the group edit form, and can have a profile view module associated with it.
To add a profile module, simply add a corresponding view as ``groups/profile/module/<tool_name>``.

.. code-block:: php

	// file: groups/profile/module/my-tool.php

	echo elgg_view('groups/profile/module', [
		'title' => elgg_echo('my-tool'),
		'content' => 'Hello, world!',
	]);

You can programmically enable and disable tools for a given group:

.. code-block:: php
	
	$group = get_entity($group_guid);
	
	// enables the file tool for the group
	$group->enableTool('file');
	
	// disables the file tool for the group
	$group->disableTool('file');

If you want to allow a certain feature in a group only if the group tool option is enabled, you can check this using ``\ElggGroup::isToolEnabled($tool_option)``.

It is also a possibility to use a gatekeeper function to prevent access to a group page based on an enabled tool.

.. code-block:: php

	elgg_group_tool_gatekeeper('file', $group);

.. seealso::

	Read more about gatekeepers here: :ref:`authentication-gatekeepers`

If you need the configured group tool options for a specific group you can use the ``elgg()->group_tools->group($group)`` function.
