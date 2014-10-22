<?php
/**
 * Friend widget display view
 *
 */

// owner of the widget
$owner = $vars['entity']->getOwnerEntity();

$num_display = sanitize_int($vars['entity']->num_display, false);
// set default value for display number
if (!$num_display) {
	$num_display = 12;
}

// get the correct size
$size = $vars['entity']->icon_size;

if (elgg_instanceof($owner, 'user')) {
	$html = elgg_list_entities_from_relationship(array(
		'type' => 'user',
		'relationship' => 'friend',
		'relationship_guid' => $owner->guid,
		'limit' => $num_display,
		'size' => $size,
		'list_type' => 'gallery',
		'pagination' => false,
		'no_results' => elgg_echo('friends:none'),
	));
	if ($html) {
		echo $html;
	}
}
