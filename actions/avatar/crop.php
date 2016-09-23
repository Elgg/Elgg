<?php
/**
 * Avatar crop action
 *
 */

$guid = get_input('guid');
$owner = get_entity($guid);

if (!$owner || !($owner instanceof ElggUser) || !$owner->canEdit()) {
	register_error(elgg_echo('avatar:crop:fail'));
	forward(REFERER);
}

$coords = [
	'x1' => (int) get_input('x1', 0),
	'y1' => (int) get_input('y1', 0),
	'x2' => (int) get_input('x2', 0),
	'y2' => (int) get_input('y2', 0),
];

// ensuring the avatar image exists in the first place
if (!$owner->hasIcon('master')) {
	register_error(elgg_echo('avatar:crop:fail'));
	forward(REFERER);
}

if (!$owner->saveIconFromElggFile($owner->getIcon('master'), 'icon', $coords)) {
	register_error(elgg_echo('avatar:crop:fail'));
	forward(REFERER);
}

system_message(elgg_echo('avatar:crop:success'));
$view = 'river/user/default/profileiconupdate';
_elgg_delete_river(array('subject_guid' => $owner->guid, 'view' => $view));
elgg_create_river_item(array(
	'view' => $view,
	'action_type' => 'update',
	'subject_guid' => $owner->guid,
	'object_guid' => $owner->guid,
));


forward(REFERER);
