<?php

/**
 * Create (or fetch an existing) named collection on an entity. Good for creating a collection
 * on demand for editing.
 *
 * @param ElggEntity $entity
 * @param string $name
 * @return Elgg_Collection|null null if user is not permitted to create
 */
function elgg_create_collection(ElggEntity $entity, $name = '__default') {
	return _elgg_services()->collections->create($entity, $name);
}

/**
 * Get a reference to a collection if it exists, and the current user can see (or can edit it)
 *
 * @param ElggEntity $entity
 * @param string $name
 * @return Elgg_Collection|null
 */
function elgg_get_collection(ElggEntity $entity, $name = '__default') {
	return _elgg_services()->collections->fetch($entity, $name);
}

/**
 * Does this collection exist? This does not imply the current user can access it.
 *
 * @param ElggEntity|int $entity entity or GUID
 * @param string $name
 * @return bool
 */
function elgg_collection_exists($entity, $name = '__default') {
	return _elgg_services()->collections->exists($entity, $name);
}

/**
 * Get a query modifier object to apply a collection to an elgg_get_entities call.
 *
 * <code>
 * $qm = elgg_get_collection_query_modifier($user, 'blog_sticky');
 * $qm->setModel('sticky');
 *
 * elgg_list_entities($qm->getOptions(array(
 *     'type' => 'object',
 *     'subtype' => 'blog',
 *     'owner_guid' => $user->guid,
 * )));
 * </code>
 *
 * @param ElggEntity|int $entity entity or GUID
 * @param string $name
 * @return Elgg_Collection_QueryModifier
 */
function elgg_get_collection_query_modifier($entity, $name = '__default') {
	$coll = _elgg_services()->collections->fetch($entity, $name);
	return new Elgg_Collection_QueryModifier($coll);
}

/**
 * Runs unit tests for collections and query modifiers
 *
 * @param string $hook   unit_test
 * @param string $type   system
 * @param mixed  $value  Array of tests
 * @param mixed  $params Params
 *
 * @return array
 * @access private
 */
function _elgg_query_modification_test($hook, $type, $value, $params) {
	global $CONFIG;
	$value[] = $CONFIG->path . 'engine/tests/ElggCoreCollectionsTest.php';
	return $value;
}

/**
 * Entities init function; establishes the default entity page handler
 *
 * @return void
 * @elgg_event_handler init system
 * @access private
 */
function _elgg_query_modification_init() {
	elgg_register_plugin_hook_handler('unit_test', 'system', '_elgg_query_modification_test');
}


elgg_register_event_handler('init', 'system', '_elgg_query_modification_init');
