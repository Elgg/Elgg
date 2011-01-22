<?php
/**
 * Pages function library
 */

/**
 * Prepare the add/edit form variables
 *
 * @param ElggObject $page
 * @return array
 */
function pages_prepare_form_vars($page = null, $parent_guid = 0) {

	// input names => defaults
	$values = array(
		'title' => '',
		'description' => '',
		'access_id' => ACCESS_DEFAULT,
		'write_access_id' => ACCESS_DEFAULT,
		'tags' => '',
		'container_guid' => elgg_get_page_owner_guid(),
		'guid' => null,
		'entity' => $page,
		'parent_guid' => $parent_guid,
	);

	if ($page) {
		foreach (array_keys($values) as $field) {
			$values[$field] = $page->$field;
		}
	}

	if (elgg_is_sticky_form('page')) {
		foreach (array_keys($values) as $field) {
			$values[$field] = elgg_get_sticky_value('page', $field);
		}
	}

	elgg_clear_sticky_form('page');

	return $values;
}

/**
 * Register the navigation menu
 * 
 * @param ElggEntity $container Container entity for the pages
 */
function pages_register_navigation_tree($container) {
	if (!$container) {
		return;
	}

	$top_pages = elgg_get_entities(array(
		'type' => 'object',
		'subtype' => 'page_top',
		'container_guid' => $container->getGUID,
	));

	foreach ($top_pages as $page) {
		elgg_register_menu_item('pages_nav', array(
			'name' => $page->getGUID(),
			'title' => $page->title,
			'url' => $page->getURL(),
		));

		$stack = array();
		array_push($stack, $page);
		while (count($stack) > 0) {
			$parent = array_pop($stack);
			$children = elgg_get_entities_from_metadata(array(
				'type' => 'object',
				'subtype' => 'page',
				'metadata_name' => 'parent_guid',
				'metadata_value' => $parent->getGUID(),
			));
			
			foreach ($children as $child) {
				elgg_register_menu_item('pages_nav', array(
					'name' => $child->getGUID(),
					'title' => $child->title,
					'url' => $child->getURL(),
					'parent_name' => $parent->getGUID(),
				));
				array_push($stack, $child);
			}
		}
	}
}

/**
 * Return the correct sidebar for a given entity
 *
 * @param ElggObject $entity
 */
function pages_get_entity_sidebar(ElggObject $entity, $fulltree = 0) {
	$body = "";

	$children = elgg_get_entities_from_metadata(array('metadata_names' => 'parent_guid', 'metadata_values' => $entity->guid, 'limit' => 9999));
	$body .= elgg_view('pages/sidebar/sidebarthis', array('entity' => $entity,
														'children' => $children,
														'fulltree' => $fulltree));
	//$body = elgg_view('pages/sidebar/wrapper', array('body' => $body));

	return $body;
}
