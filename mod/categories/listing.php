<?php
/**
 * List entities by category
 *
 * @package ElggCategories
 */

$limit = get_input("limit", 10);
$offset = get_input("offset", 0);
$category = get_input("category");
$owner_guid = get_input("owner_guid", ELGG_ENTITIES_ANY_VALUE);
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
$objects = elgg_list_entities_from_metadata($params);

$title = elgg_echo('categories:results', array($category));

$content = elgg_view_title($title);
$content .= $objects;

$body = elgg_view_layout('two_column_left_sidebar', '', $content);

echo elgg_view_page($title, $body);
