Capabilities
############

.. contents:: Contents
   :local:
   :depth: 1

Entity Capabilities
===================

Defining capabilities
---------------------

There is no need to explicitly define or register a new capability to the system. For example the `search` plugin uses the `searchable` capability. 

Registering for capabilities
----------------------------

If an entity supports a certain capability (or feature) this should be registered in the system. This can be done by registering the capability in the `entities`
section of the `elgg-plugin.php` of the plugin.

.. code-block:: php

	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'blog',
			'capabilities' => [
				'searchable' => true,
			],
		],
	],
	
There is also the option to enable (or disable) a capability for a certain entity type/subtype using one of the following functions:

 * ``elgg_entity_enable_capability($type, $subtype, $capability)`` use this for enabling a certain capability
 * ``elgg_entity_disable_capability($type, $subtype, $capability)`` use this for disabling a certain capability

Checking for capabilities
-------------------------

There are helper functions to check if a certain capability is supported in the system. 
You can check if an entity supports a certain capability using the `$entity->hasCapability($capability)` function. 
Alternatively if you do not have an entity at your disposal, you can use `elgg_entity_has_capability($type, $subtype, $capability)`.

There is also a function available to get an array of all type/subtypes in the system that support a certain capability.

.. code-block:: php

	$types_subtypes = elgg_entity_types_with_capability('searchable');
	
	// output
	[
		'object' => [
			'blog',
			'page',
		],
		'group' => [
			'group',
		],
	]
