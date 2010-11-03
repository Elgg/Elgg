<?php
/**
 * Elgg categories listing page
 *
 * @package ElggCategories
 */

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

$limit = get_input("limit", 10);
$offset = get_input("offset", 0);
$category = get_input("category");
$owner_guid = get_input("owner_guid", 0);
$subtype = get_input("subtype", ELGG_ENTITIES_ANY_VALUE);
$type = get_input("type", 'object');

$params = array(
	'metadata_name' => 'universal_categories',
	'metadata_value' => $category,
	'types' => $type,
	'subtypes' => $subtype,
	'owner_guid' => $owner_guid,
	'limit' => $limit,
	'full_view' => FALSE,
	'metadata_case_sensitive' => FALSE,
);
elgg_push_context('search');
$objects = elgg_list_entities_from_metadata($params);
elgg_pop_context();

$title = elgg_echo('categories:results', array($category));

$content = elgg_view_title($title);
$content .= $objects;

$body = elgg_view_layout('two_column_left_sidebar', '', $content);

echo elgg_view_page($title, $body);
