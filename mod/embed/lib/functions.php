<?php
/**
 * Holds helper functions for embed plugin
 */

/**
 * A special listing function for selectable content
 *
 * This calls a custom list view for entities.
 *
 * @param array $entities Array of ElggEntity objects
 * @param array $vars     Display parameters
 * @return string
 */
function embed_list_items($entities, $vars = []) {

	$defaults = [
		'items' => $entities,
		'list_class' => 'elgg-list-entity',
	];

	$vars = array_merge($defaults, $vars);

	return elgg_view('embed/list', $vars);
}

/**
 * Set the options for the list of embedable content
 *
 * @param array $options additional options
 *
 * @return array
 */
function embed_get_list_options($options = []) {

	$container_guids = [elgg_get_logged_in_user_guid()];
	if (elgg_get_page_owner_guid()) {
		$page_owner_guid = elgg_get_page_owner_guid();
		if ($page_owner_guid != elgg_get_logged_in_user_guid()) {
			$container_guids[] = $page_owner_guid;
		}
	}

	$defaults = [
		'limit' => 6,
		'container_guids' => $container_guids,
		'item_class' => 'embed-item',
		'no_results' => true,
	];

	$options = array_merge($defaults, $options);

	return $options;
}
