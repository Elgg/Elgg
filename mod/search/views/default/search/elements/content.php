<?php
/**
 * Default view for content used in search results
 *
 * Display largely controlled by a set of overrideable volatile data:
 *   - search_matched_description (defaults to entity->description)
 *   - search_matched_extra (defaults to matched tag)
 *
 * @uses $vars['entity'] Entity returned in a search
 */
$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	return;
}

$search_params = elgg_extract('params', $vars);

$description = $entity->getVolatileData('search_matched_description');
if (empty($description)) {
	$description = $entity->description;
	
	$highlighter = elgg_extract('highlighter', $vars);
	if ($highlighter instanceof \Elgg\Search\Highlighter) {
		$description = $highlighter->highlight($description, 10, 300);
	}
}

if ($description) {
	echo elgg_format_element('div', ['class' => 'search-matched-description'], $description);
}

$extra_info = $entity->getVolatileData('search_matched_extra');
if (empty($extra_info) && isset($search_params['fields']['metadata']) && in_array('tags', $search_params['fields']['metadata'])) {
	$query = elgg_extract('query', $search_params);
	$tags = (array) $entity->tags;
	
	if (in_array($query, $tags)) {
		$extra_info = elgg_view('output/tags', ['tags' => $query]);
	}
}

if ($extra_info) {
	echo elgg_format_element('div', ['class' => 'search-matched-extra'], $extra_info);
}
