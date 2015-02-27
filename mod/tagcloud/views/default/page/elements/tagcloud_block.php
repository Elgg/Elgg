<?php
/**
 * Display content-based tags
 *
 * @uses $vars['subtypes']       Object subtype string or array of subtypes
 * @uses $vars['owner_guid']     The owner of the content being tagged
 * @uses $vars['container_guid'] The container of the content being tagged
 * @uses $vars['limit']          The maximum number of tags to display
 */

$owner_guid = elgg_extract('owner_guid', $vars, ELGG_ENTITIES_ANY_VALUE);
$container_guid = elgg_extract('container_guid', $vars, ELGG_ENTITIES_ANY_VALUE);

$type = elgg_extract('type', $vars, 'object');

$options = array(
	'type' => $type,
	'subtype' => elgg_extract('subtypes', $vars, ELGG_ENTITIES_ANY_VALUE),
	'owner_guid' => $owner_guid,
	'container_guid' => $container_guid,
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
	'is_trusted' => true,
));
$cloud .= '</p>';


echo elgg_view_module('aside', $title, $cloud);
