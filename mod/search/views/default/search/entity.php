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

$params = elgg_extract('params', $vars, []);

$service = new \Elgg\Search\Search($params);

$service->prepareEntity($entity);

$view = $service->getSearchView();

if ($view && $view != 'search/entity' && elgg_view_exists($view)) {
	$vars['entity'] = $entity;
	echo elgg_view($view, $vars);

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

$subtitle = '';

$type = $entity->getType();
if ($type == 'object') {
	$subtitle = elgg_view('page/elements/by_line', [
		'entity' => $entity,
		'time' => $entity->getVolatileData('search_time'),
	]);
}

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
echo elgg_view("$type/elements/summary", [
	'entity' => $entity,
	'tags' => false,
	'title' => $title,
	'subtitle' => $subtitle,
	'content' => $content,
	'icon' => $icon,
]);
