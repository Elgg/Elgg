<?php
/**
 * Display the latest related comments
 *
 * Generally used in a sidebar. Does not work with groups currently.
 *
 * @uses $vars['subtypes']   Object subtype string or array of subtypes
 * @uses $vars['owner_guid'] The owner of the content being commented on
 * @uses $vars['limit']      The number of comments to display
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
	'annotation_name' => 'generic_comment',
	'owner_guid' => $owner_guid,
	'reverse_order_by' => true,
	'limit' => elgg_extract('limit', $vars, 4),
	'type' => 'object',
	'subtypes' => elgg_extract('subtypes', $vars, ELGG_ENTITIES_ANY_VALUE),
);

$title = elgg_echo('generic_comments:latest');
$comments = elgg_get_annotations($options);
if ($comments) {
	$body = elgg_view('page/components/list', array(
		'items' => $comments,
		'pagination' => false,
		'list_class' => 'elgg-latest-comments',
	));
} else {
	$body = '<p>' . elgg_echo('generic_comment:none') . '</p>';
}

echo elgg_view_module('aside', $title, $body);
