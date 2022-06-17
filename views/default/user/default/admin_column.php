<?php
/**
 * Elgg user display
 *
 * @uses $vars['entity'] ElggUser entity
 * @uses $vars['title']  Optional override for the title
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggUser) {
	return;
}

$title = elgg_extract('title', $vars);
if (!isset($title)) {
	$title = elgg_view_entity_url($entity);
	$title .= elgg_format_element('span', [
		'class' => ['elgg-quiet', 'mls'],
		'title' => elgg_echo('table_columns:fromProperty:username'),
	], "({$entity->username})");
}

$metadata = elgg_view('output/url', [
	'icon' => 'info-circle',
	'text' => elgg_echo('more_info'),
	'href' => elgg_http_add_url_query_elements('ajax/view/admin/users/listing/details', [
		'guid' => $entity->guid,
	]),
	'class' => ['elgg-lightbox', 'float-alt'],
	'data-colorbox-opts' => json_encode([
		'innerWidth' => '800px',
		'maxHeight' => '90%',
	]),
]);

$params = [
	'entity' => $entity,
	'title' => $title,
	'metadata' => $metadata,
	'icon' => false,
];
$params = $params + $vars;
echo elgg_view('user/elements/summary', $params);
