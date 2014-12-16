<?php
/**
 * Pages function library
 */

/**
 * Prepare the add/edit form variables
 *
 * @param ElggObject     $page
 * @param int            $parent_guid
 * @param ElggAnnotation $revision
 * @return array
 */
function pages_prepare_form_vars($page = null, $parent_guid = 0, $revision = null) {

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
			if (isset($page->$field)) {
				$values[$field] = $page->$field;
			}
		}
	}

	if (elgg_is_sticky_form('page')) {
		$sticky_values = elgg_get_sticky_values('page');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('page');

	// load the revision annotation if requested
	if ($revision instanceof ElggAnnotation && $revision->entity_guid == $page->getGUID()) {
		$values['description'] = $revision->value;
	}

	return $values;
}

/**
 * Recurses the page tree and adds the breadcrumbs for all ancestors
 *
 * @param ElggObject $page Page entity
 */
function pages_prepare_parent_breadcrumbs($page) {
	if ($page && $page->parent_guid) {
		$parents = array();
		$parent = get_entity($page->parent_guid);
		while ($parent) {
			array_push($parents, $parent);
			$parent = get_entity($parent->parent_guid);
		}
		while ($parents) {
			$parent = array_pop($parents);
			elgg_push_breadcrumb($parent->title, $parent->getURL());
		}
	}
}

/**
 * Produce the navigation tree
 * 
 * @param ElggEntity $container Container entity for the pages
 *
 * @return array
 */
function pages_get_navigation_tree($container) {
	if (!elgg_instanceof($container)) {
		return;
	}

	$top_pages = new ElggBatch('elgg_get_entities', array(
		'type' => 'object',
		'subtype' => 'page_top',
		'container_guid' => $container->getGUID(),
		'limit' => false,
	));

	/* @var ElggBatch $top_pages Batch of top level pages */

	$tree = array();
	$depths = array();

	foreach ($top_pages as $page) {
		$tree[] = array(
			'guid' => $page->getGUID(),
			'title' => $page->title,
			'url' => $page->getURL(),
			'depth' => 0,
		);
		$depths[$page->guid] = 0;

		$stack = array();
		array_push($stack, $page);
		while (count($stack) > 0) {
			$parent = array_pop($stack);
			$children = new ElggBatch('elgg_get_entities_from_metadata', array(
				'type' => 'object',
				'subtype' => 'page',
				'metadata_name' => 'parent_guid',
				'metadata_value' => $parent->getGUID(),
				'limit' => false,
			));

			foreach ($children as $child) {
				$tree[] = array(
					'guid' => $child->getGUID(),
					'title' => $child->title,
					'url' => $child->getURL(),
					'parent_guid' => $parent->getGUID(),
					'depth' => $depths[$parent->guid] + 1,
				);
				$depths[$child->guid] = $depths[$parent->guid] + 1;
				array_push($stack, $child);
			}
		}
	}
	return $tree;
}

/**
 * Register the navigation menu
 * 
 * @param ElggEntity $container Container entity for the pages
 */
function pages_register_navigation_tree($container) {
	$pages = pages_get_navigation_tree($container);
	if ($pages) {
		foreach ($pages as $page) {
			elgg_register_menu_item('pages_nav', array(
				'name' => $page['guid'],
				'text' => $page['title'],
				'href' => $page['url'],
				'parent_name' => $page['parent_guid'],
			));
		}
	}
}

/**
 * Function checking delete permission
 *
 * @package ElggPages
 * @param mixed $value
 *
 * @return bool
 */
function pages_can_delete_page($page) {
	if (! $page) {
		return false;
	} else {
		$container = get_entity($page->container_guid);
		return $container ? $container->canEdit() : false;
	}
}
