Group Tools
===========

In Elgg groups have a feature where you can enable or disable different tools for a group. These tools are provided by other plugins like blog or file.

Plugins need to tell Elgg that these tools exist. This can be done by using the ``add_group_tool_option`` function.
If an existing tool option needs to be removed you can use ``remove_group_tool_option``.

On a group edit form you can turn the tools on or off for the specific group. You can also do so programmatically as shown in the code example below. 

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

If you need the configured group tool options for a specific group you can use the ``elgg_get_group_tool_options($group)`` function.
