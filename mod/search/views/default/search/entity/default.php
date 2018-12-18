<?php

/**
 * Default view for an entity returned in a search
 *
 * Display largely controlled by a set of overrideable volatile data:
 *   - search_icon (defaults to entity icon)
 *   - search_matched_title
 *   - search_matched_description
 *   - search_matched_extra
 *   - search_url (defaults to entity->getURL())
 *   - search_time (defaults to entity->time_updated or entity->time_created)
 *
 * @uses $vars['entity'] Entity returned in a search
 */
$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	return;
}

$title = $entity->getVolatileData('search_matched_title');
$description = $entity->getVolatileData('search_matched_description');
$extra_info = $entity->getVolatileData('search_matched_extra');
$icon = $entity->getVolatileData('search_icon');
$url = $entity->getVolatileData('search_url');

$title = elgg_view('output/url', [
	'text' => $title,
	'href' => $url,
	'class' => 'search-matched-title',
]);

$type = $entity->getType();

$content = '';
if ($description) {
	$content .= elgg_format_element('div', [
		'class' => 'search-matched-description',
	], $description);
}
if ($extra_info) {
	$content .= elgg_format_element('div', [
		'class' => 'search-matched-extra',
	], $extra_info);
}

$params = [
	'entity' => $entity,
	'tags' => false,
	'title' => $title,
	'subtitle' => ($type == 'object') ? null : '',
	'time' => $entity->getVolatileData('search_time'),
	'access' => false,
	'content' => $content,
	'icon' => $icon,
];
$params = $params + $vars;

echo elgg_view("$type/elements/summary", $params);
