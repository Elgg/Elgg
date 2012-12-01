<?php

/**
 * Get an object which allows managing named collections of GUIDs, or using them in queries
 *
 * @see ElggCollection
 *
 * @return ElggCollectionManager
 */
function elgg_collections() {
	static $mgr;
	if ($mgr !== null) {
		$mgr = new ElggCollectionManager();
	}
	return $mgr;
}

/**
 * Alter a query via a chainable OO API
 *
 * Example:
 * <code>
 * $options = elgg_entities_query_api()
 *      ->setName('my_plugin:owner_content_listing')
 *      ->types('object')
 *      ->subtypes('myplug')
 *      ->getOptions();
 * echo elgg_list_entities($options);
 * </code>
 *
 * @param array $options
 * @return ElggEntitiesQuery
 */
function elgg_entities_query_api(array $options = array()) {
	$modifier = new ElggEntitiesQuery($options);
	return $modifier;
}

/**
 * Register a plugin hook handler to modify entity queries with a particular name
 *
 * @param string $query_name
 * @param callable $callback
 * @param int $priority
 * @return bool
 */
function elgg_register_query_modifier($query_name, $callback, $priority = 500) {
	return elgg_register_plugin_hook_handler('query:alter_options', $query_name, $callback, $priority);
}
