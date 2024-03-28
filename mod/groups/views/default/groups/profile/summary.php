<?php
/**
 * Group profile summary
 *
 * Icon and profile fields
 *
 * @uses $vars['entity']
 */

$group = elgg_extract('entity', $vars);
if (!$group instanceof \ElggGroup) {
	echo elgg_echo('groups:notfound');
	return;
}

// we don't force icons to be square so don't set width/height
$icon = elgg_format_element('div', [
	'class' => 'groups-profile-icon',
], elgg_view_entity_icon($group, 'large', ['href' => false]));

$body = '';

$fields = elgg_view('groups/profile/fields', $vars);
if (!empty($fields)) {
	$body = elgg_format_element('div', ['class' => 'groups-profile-fields'], $fields);
}

echo elgg_view_image_block($icon, $body, ['class' => 'groups-profile']);
