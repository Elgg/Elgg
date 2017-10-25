<?php
/**
 * Avatar crop action
 */

$guid = (int) get_input('guid');
$owner = get_user($guid);

if (!$owner || !$owner->canEdit()) {
	return elgg_error_response(elgg_echo('avatar:crop:fail'));
}

// ensuring the avatar image exists in the first place
if (!$owner->hasIcon('master')) {
	return elgg_error_response(elgg_echo('avatar:crop:fail'));
}

$coords = [
	'x1' => (int) get_input('x1', 0),
	'y1' => (int) get_input('y1', 0),
	'x2' => (int) get_input('x2', 0),
	'y2' => (int) get_input('y2', 0),
];

if (!$owner->saveIconFromElggFile($owner->getIcon('master'), 'icon', $coords)) {
	return elgg_error_response(elgg_echo('avatar:crop:fail'));
}

// River
$view = 'river/user/default/profileiconupdate';

// remove old river items
elgg_delete_river([
	'subject_guid' => $owner->guid,
	'view' => $view,
	'limit' => false,
]);
// create new river entry
elgg_create_river_item([
	'view' => $view,
	'action_type' => 'update',
	'subject_guid' => $owner->guid,
	'object_guid' => $owner->guid,
]);

return elgg_ok_response('', elgg_echo('avatar:crop:success'));
