<?php
/**
 * History of revisions of a page
 */

use Elgg\Database\Clauses\OrderByClause;

$page_guid = (int) elgg_extract('guid', $vars);

elgg_entity_gatekeeper($page_guid, 'object', 'page', true);

/* @var $page \ElggPage */
$page = get_entity($page_guid);

elgg_push_collection_breadcrumbs('object', 'page', elgg_get_page_owner_entity());

pages_prepare_parent_breadcrumbs($page);

$title = "{$page->getDisplayName()}: " . elgg_echo('pages:history');

$content = elgg_list_annotations([
	'guid' => $page_guid,
	'annotation_name' => 'page',
	'limit' => max(20, elgg_get_config('default_limit')),
	'order_by' => [
		new OrderByClause('a_table.time_created', 'desc'),
		new OrderByClause('a_table.id', 'desc'),
	],
	'no_results' => elgg_echo('pages:none'),
]);

echo elgg_view_page($title, [
	'content' => $content,
	'filter_id' => 'pages/history',
	'filter_value' => 'history',
]);
