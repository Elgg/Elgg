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


$query = elgg_extract('query', $vars);
$entity = elgg_extract('entity', $vars);
$size = elgg_extract('size', $vars, 'small');

$type = $entity->getType();
$subtype = $entity->getSubtype();
$owner = $entity->getOwnerEntity();
$container = $entity->getContainerEntity();

if (!$entity->getVolatileData('search_matched_title')) {
	$title = search_get_highlighted_relevant_substrings($entity->getDisplayName(), $query);
	$entity->setVolatileData('search_matched_title', $title);
}

if (!$entity->getVolatileData('search_matched_description')) {
	$desc = search_get_highlighted_relevant_substrings($entity->description, $query);
	$entity->setVolatileData('search_matched_description', $desc);
}

if (!$entity->getVolatileData('search_matched_extra')) {

	switch ($type) {
		case 'user' :
			$fields = array_keys(elgg_get_config('profile_fields'));
			$prefix = 'profile';
			$exclude = array('name', 'description', 'briefdescription');
			break;
		case 'group' :
			$fields = array_keys(elgg_get_config('group'));
			$prefix = 'group';
			$exclude = array('name', 'description', 'briefdescription');
			break;
		case 'object' :
			$fields = elgg_get_registered_tag_metadata_names();
			$prefix = 'tag_names';
			$exclude = array('title', 'description');
			break;
	}

	$matches = array();
	foreach ($fields as $field) {
		if (in_array($field, $exclude)) {
			continue;
		}
		$metadata = $entity->$field;
		if (is_array($metadata)) {
			foreach ($metadata as $text) {
				if (stristr($text, $query)) {
					$matches["$prefix:$field"][] = search_get_highlighted_relevant_substrings($text, $query);
				}
			}
		} else {
			if (stristr($metadata, $query)) {
				$matches["$prefix:$field"][] = search_get_highlighted_relevant_substrings($metadata, $query);
			}
		}
	}

	$extra = array();
	foreach ($matches as $label => $match) {
		$extra[] = elgg_format_element('span', [
					'class' => 'search-match-extra-label',
						], elgg_echo($label)) . implode(', ', $match);
	}

	$entity->setVolatileData('search_matched_extra', implode('<br />', $extra));
}

$view = search_get_search_view($vars['params'], 'entity');
if ($view != 'search/entity' && elgg_view_exists($view)) {
	$vars['entity'] = $entity;
	echo elgg_view($view, $vars);
	return;
}

$icon = $entity->getVolatileData('search_icon');
if (!$icon) {
	if ($entity->hasIcon($size)) {
		$icon = elgg_view_entity_icon($entity, $size);
	} else if ($type == 'user' || $type == 'group') {
		$icon = elgg_view_entity_icon($entity, $size);
	} elseif ($owner instanceof ElggUser) {
		$icon = elgg_view_entity_icon($owner, $size);
	} else if ($container instanceof ElggUser) {
		// display a generic icon if no owner, though there will probably be
		// other problems if the owner can't be found.
		$icon = elgg_view_entity_icon($entity, $size);
	}
}

$title = $entity->getVolatileData('search_matched_title');
$description = $entity->getVolatileData('search_matched_description');
$extra_info = $entity->getVolatileData('search_matched_extra');

$url = $entity->getVolatileData('search_url');
if (!$url) {
	$url = $entity->getURL();
}

$title = elgg_view('output/url', array(
	'text' => $title,
	'href' => $url,
	'class' => 'search-matched-title',
		));

$subtitle = '';
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
echo elgg_view("$type/elements/summary", array(
	'entity' => $entity,
	'tags' => false,
	'title' => $title,
	'subtitle' => $subtitle,
	'content' => $content,
	'icon' => $icon,
));
