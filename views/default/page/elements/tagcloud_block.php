<?php
/**
 * Display content-based tags
 *
 * Generally used in a sidebar. Does not work with groups currently.
 *
 * @uses $vars['subtypes']   Object subtype string or array of subtypes
 * @uses $vars['owner_guid'] The owner of the content being tagged
 * @uses $vars['limit']      The maxinum number of tags to display
 */

$owner_guid = elgg_extract('owner_guid', $vars, ELGG_ENTITIES_ANY_VALUE);
if (!$owner_guid) {
	$owner_guid = ELGG_ENTITIES_ANY_VALUE;
}

$owner_entity = get_entity($owner_guid);
if ($owner_entity && elgg_instanceof($owner_entity, 'group')) {
	// not supporting groups so return
	return true;
}

$options = array(
	'type' => 'object',
	'subtype' => elgg_extract('subtypes', $vars, ELGG_ENTITIES_ANY_VALUE),
	'owner_guid' => $owner_guid,
	'threshold' => 0,
	'limit' => elgg_extract('limit', $vars, 50),
	'tag_name' => 'tags',
);

$title = elgg_echo('tagcloud');
if (is_array($options['subtype']) && count($options['subtype']) > 1) {
	// we cannot provide links to tagged objects with multiple types
	$tag_data = elgg_get_tags($options);
	$cloud = elgg_view("output/tagcloud", array(
		'value' => $tag_data,
		'type' => $type,
	));
} else {
	$cloud = elgg_view_tagcloud($options);
}
if (!$cloud) {
	return true;
}

// add a link to all site tags
$cloud .= '<p class="small">';
$cloud .= elgg_view_icon('tag');
$cloud .= elgg_view('output/url', array(
	'href' => 'tags',
	'text' => elgg_echo('tagcloud:allsitetags'),
));
$cloud .= '</p>';


echo elgg_view_module('aside', $title, $cloud);
