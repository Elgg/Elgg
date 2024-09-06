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

if ($group->canAccessContent()) {
	if (!$group->isPublicMembership() && !$group->isMember()) {
		$body .= elgg_view_message('notice', elgg_echo('groups:closedgroup'), ['title' => false]);
	}
} else {
	if ($group->isPublicMembership()) {
		$body .= elgg_view_message('notice', elgg_echo('groups:opengroup:membersonly'), ['title' => false]);
	} else {
		$body .= elgg_view_message('notice', elgg_echo('groups:closedgroup:membersonly'), ['title' => false]);
	}
}

$fields = elgg_view('groups/profile/fields', $vars);
if (!empty($fields)) {
	$body .= elgg_format_element('div', ['class' => 'groups-profile-fields'], $fields);
}

echo elgg_view_image_block($icon, $body, ['class' => 'groups-profile']);
