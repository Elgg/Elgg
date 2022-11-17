<?php
/**
 * Pages function library
 */

/**
 * Recurses the page tree and adds the breadcrumbs for all ancestors
 *
 * @param ElggPage $page Page entity
 *
 * @return void
 */
function pages_prepare_parent_breadcrumbs(\ElggPage $page): void {
	$crumbs = [];

	while ($page instanceof \ElggPage) {
		$crumbs[] = [
			'text' => $page->getDisplayName(),
			'href' => $page->getURL(),
		];
		$page = $page->getParentEntity();
	}

	array_shift($crumbs);
	$crumbs = array_reverse($crumbs);

	foreach ($crumbs as $crumb) {
		elgg_push_breadcrumb($crumb['text'], $crumb['href']);
	}
}

/**
 * Produce the navigation tree
 *
 * @param ElggEntity $container Container entity for the pages
 *
 * @return array
 */
function pages_get_navigation_tree(\ElggEntity $container): array {
	$top_pages = elgg_get_entities([
		'type' => 'object',
		'subtype' => 'page',
		'container_guid' => $container->guid,
		'limit' => false,
		'batch' => true,
		'metadata_name_value_pairs' => [
			'parent_guid' => 0,
		],
	]);

	$tree = [];
	
	$get_children = function($parent_guid, $depth = 0) use (&$tree, &$get_children) {
		$children = new ElggBatch('elgg_get_entities', [
			'type' => 'object',
			'subtype' => 'page',
			'metadata_name_value_pairs' => [
				'parent_guid' => $parent_guid,
			],
			'limit' => false,
		]);
		
		foreach ($children as $child) {
			$tree[] = [
				'guid' => $child->guid,
				'title' => $child->getDisplayName(),
				'url' => $child->getURL(),
				'parent_guid' => $parent_guid,
				'depth' => $depth + 1,
			];
			
			$get_children($child->guid, $depth + 1);
		}
	};
	
	/* @var $page ElggPage */
	foreach ($top_pages as $page) {
		$tree[] = [
			'guid' => $page->guid,
			'title' => $page->getDisplayName(),
			'url' => $page->getURL(),
			'depth' => 0,
		];
		
		$get_children($page->guid);
	}

	return $tree;
}
