<?php
/**
 * Elgg annotations
 * Functions to manage object annotations.
 */

use Elgg\Menu\MenuItems;

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
	return _elgg_services()->annotationsTable->get($id);
}

/**
 * Deletes an annotation using its ID.
 *
 * @param int $id The annotation ID to delete.
 *
 * @return bool
 */
function elgg_delete_annotation_by_id($id) {
	$annotation = elgg_get_annotation_from_id($id);
	if (!$annotation) {
		return false;
	}

	return $annotation->delete();
}

/**
 * Fetch annotations or perform a calculation on them
 *
 * Accepts all options supported by {@link elgg_get_entities()}
 *
 * The default 'order_by' is 'n_table.time_created, n_table.id',
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
	return _elgg_services()->annotationsTable->find($options);
}

/**
 * Returns a rendered list of annotations with pagination.
 *
 * @param array $options Annotation getter and display options.
 *                       {@link elgg_get_annotations()} and {@link elgg_list_entities()}.
 *
 * @return string The list of entities
 * @since 1.8.0
 */
function elgg_list_annotations($options) {
	$defaults = [
		'limit' => 25,
		'offset' => (int) max(get_input('annoff', 0), 0),
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
	return _elgg_services()->annotationsTable->deleteAll($options);
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
	return _elgg_services()->annotationsTable->disableAll($options);
}

/**
 * Enables annotations based on $options.
 *
 * @warning Unlike elgg_get_annotations() this will not accept an empty options array!
 *
 * @param array $options An options array. {@link elgg_get_annotations()}
 * @return bool|null true on success, false on failure, null if no metadata enabled.
 * @since 1.8.0
 */
function elgg_enable_annotations(array $options) {
	return _elgg_services()->annotationsTable->enableAll($options);
}

/**
 * Check to see if a user has already created an annotation on an object
 *
 * @param int    $entity_guid Entity guid
 * @param string $name        Annotation name
 * @param int    $owner_guid  Defaults to logged in user.
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_annotation_exists($entity_guid, $name, $owner_guid = null) {
	$owner_guid = (int) $owner_guid;
	if ($owner_guid < 1) {
		$owner_guid = elgg_get_logged_in_user_guid();
	}

	return _elgg_services()->annotationsTable->exists($entity_guid, $name, $owner_guid);
}

/**
 * Register default menu items for an annotation
 *
 * @param \Elgg\Hook $hook 'register', 'menu:annotation'
 *
 * @return void|MenuItems
 * @internal
 * @since 3.3
 */
function _elgg_annotations_default_menu_items(\Elgg\Hook $hook) {
	
	$annotation = $hook->getParam('annotation');
	if (!$annotation instanceof ElggAnnotation) {
		return;
	}
	
	/* @var $result MenuItems */
	$result = $hook->getValue();
	
	if ($annotation->canEdit()) {
		$result[] = ElggMenuItem::factory([
			'name' => 'delete',
			'icon' => 'delete',
			'text' => elgg_echo('delete'),
			'href' => elgg_generate_action_url('annotation/delete', [
				'id' => $annotation->id,
			]),
			'confirm' => elgg_echo('deleteconfirm'),
		]);
	}
	
	return $result;
}

/**
 * Init annotations
 *
 * @return void
 * @internal
 * @since 3.3
 */
function _elgg_annotations_init() {
	
	elgg_register_plugin_hook_handler('register', 'menu:annotation', '_elgg_annotations_default_menu_items');
}

/**
 * @see \Elgg\Application::loadCore Do not do work here. Just register for events.
 */
return function(\Elgg\EventsService $events) {
	$events->registerHandler('init', 'system', '_elgg_annotations_init');
};
