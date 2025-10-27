<?php
use Elgg\Database\Clauses\OrderByClause;

$guid = (int) elgg_extract('guid', $vars);

/** @var \ElggPage $entity */
$entity = elgg_entity_gatekeeper($guid, 'object', 'page', true);

elgg_push_collection_breadcrumbs('object', 'page', elgg_get_page_owner_entity());

pages_prepare_parent_breadcrumbs($entity);

$title = "{$entity->getDisplayName()}: " . elgg_echo('pages:history');

$content = elgg_list_annotations([
	'guid' => $guid,
	'annotation_name' => 'page',
	'limit' => max(20, elgg_get_config('default_limit')),
	'order_by' => [
		new OrderByClause('a_table.time_created', 'desc'),
		new OrderByClause('a_table.id', 'desc'),
	],
	'no_results' => elgg_echo('list:object:page:no_results'),
]);

echo elgg_view_page($title, [
	'content' => $content,
	'filter_id' => 'pages/history',
	'filter_value' => 'history',
]);
