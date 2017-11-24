<?php
/**
 * Elgg annotations
 * Functions to manage object annotations.
 */

/**
 * Get a specific annotation by its id.
 * If you want multiple annotation objects, use
 * {@link elgg_get_annotations()}.
 *
 * @param int $id The id of the annotation object being retrieved.
 *
 * @return \ElggAnnotation|false
 */
function elgg_get_annotation_from_id($id) {
	return _elgg_services()->annotations->get($id);
}

/**
 * Deletes an annotation using its ID.
 *
 * @param int $id The annotation ID to delete.
 * @return bool
 */
function elgg_delete_annotation_by_id($id) {
	return _elgg_services()->annotations->delete($id);
}

/**
 * Create a new annotation.
 *
 * @param int    $entity_guid GUID of entity to be annotated
 * @param string $name        Name of annotation
 * @param string $value       Value of annotation
 * @param string $value_type  Type of value (default is auto detection)
 * @param int    $owner_guid  Owner of annotation (default is logged in user)
 * @param int    $access_id   Access level of annotation
 *
 * @return int|bool id on success or false on failure
 */
function create_annotation($entity_guid, $name, $value, $value_type = '',
		$owner_guid = 0, $access_id = ACCESS_PRIVATE) {
	return _elgg_services()->annotations->create(
		$entity_guid, $name, $value, $value_type, $owner_guid, $access_id);
}

/**
 * Update an annotation.
 *
 * @param int    $annotation_id Annotation ID
 * @param string $name          Name of annotation
 * @param string $value         Value of annotation
 * @param string $value_type    Type of value
 * @param int    $owner_guid    Owner of annotation
 * @param int    $access_id     Access level of annotation
 *
 * @return bool
 */
function update_annotation($annotation_id, $name, $value, $value_type, $owner_guid, $access_id) {
	return _elgg_services()->annotations->update($annotation_id, $name, $value, $value_type, $owner_guid, $access_id);
}

/**
 * Fetch annotations or perform a calculation on them
 *
 * Accepts all options supported by {@link elgg_get_entities()}
 *
 * @see   elgg_get_entities()
 *
 * @param array $options Options
 *
 * @return \ElggAnnotation[]|mixed
 *
 * @see   elgg_get_entities()
 * @since 1.8.0
 */
function elgg_get_annotations(array $options = []) {
	return _elgg_services()->annotations->find($options);
}

/**
 * Returns a rendered list of annotations with pagination.
 *
 * @param array $options Annotation getter and display options.
 * {@link elgg_get_annotations()} and {@link elgg_list_entities()}.
 *
 * @return string The list of entities
 * @since 1.8.0
 */
function elgg_list_annotations($options) {
	$defaults = [
		'limit' => 25,
		'offset' => (int) max(get_input('annoff', 0), 0),
		'no_results' => '',
	];

	$options = array_merge($defaults, $options);

	return elgg_list_entities($options, 'elgg_get_annotations', 'elgg_view_annotation_list');
}

/**
 * Deletes annotations based on $options.
 *
 * @warning Unlike elgg_get_annotations() this will not accept an empty options array!
 *          This requires at least one constraint: annotation_owner_guid(s),
 *          annotation_name(s), annotation_value(s), or guid(s) must be set.
 *
 * @param array $options An options array. {@link elgg_get_annotations()}
 * @return bool|null true on success, false on failure, null if no annotations to delete.
 * @since 1.8.0
 */
function elgg_delete_annotations(array $options) {
	return _elgg_services()->annotations->deleteAll($options);
}

/**
 * Disables annotations based on $options.
 *
 * @warning Unlike elgg_get_annotations() this will not accept an empty options array!
 *
 * @param array $options An options array. {@link elgg_get_annotations()}
 * @return bool|null true on success, false on failure, null if no annotations disabled.
 * @since 1.8.0
 */
function elgg_disable_annotations(array $options) {
	return _elgg_services()->annotations->disableAll($options);
}

/**
 * Enables annotations based on $options.
 *
 * @warning Unlike elgg_get_annotations() this will not accept an empty options array!
 *
 * @warning In order to enable annotations, you must first use
 * {@link access_show_hidden_entities()}.
 *
 * @param array $options An options array. {@link elgg_get_annotations()}
 * @return bool|null true on success, false on failure, null if no metadata enabled.
 * @since 1.8.0
 */
function elgg_enable_annotations(array $options) {
	return _elgg_services()->annotations->enableAll($options);
}

/**
 * Check to see if a user has already created an annotation on an object
 *
 * @param int    $entity_guid     Entity guid
 * @param string $annotation_type Type of annotation
 * @param int    $owner_guid      Defaults to logged in user.
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_annotation_exists($entity_guid, $annotation_type, $owner_guid = null) {
	return _elgg_services()->annotations->exists($entity_guid, $annotation_type, $owner_guid);
}

/**
 * Set the URL for a comment when called from a plugin hook
 *
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param string $url    URL string
 * @param array  $params Parameters of the hook
 * @return string
 * @access private
 */
function _elgg_set_comment_url($hook, $type, $url, $params) {
	$annotation = $params['extender'];
	/* @var \ElggExtender $annotation */
	if ($annotation->getSubtype() == 'generic_comment') {
		$entity = $annotation->getEntity();
		if ($entity) {
			return $entity->getURL() . '#item-annotation-' . $annotation->id;
		}
	}
}

/**
 * Register annotation unit tests
 *
 * @param string $hook  'unit_test'
 * @param string $type  'system'
 * @param array  $tests current return value
 *
 * @return array
 *
 * @access private
 */
function _elgg_annotations_test($hook, $type, $tests) {
	$tests[] = ElggCoreAnnotationAPITest::class;
	return $tests;
}

/**
 * Initialize the annotation library
 *
 * @return void
 *
 * @access private
 */
function _elgg_annotations_init() {
	elgg_register_plugin_hook_handler('extender:url', 'annotation', '_elgg_set_comment_url');
	elgg_register_plugin_hook_handler('unit_test', 'system', '_elgg_annotations_test');
}

/**
 * @see \Elgg\Application::loadCore Do not do work here. Just register for events.
 */
return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', '_elgg_annotations_init');
};
