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

$x1 = (int) get_input('x1', 0);
$y1 = (int) get_input('y1', 0);
$x2 = (int) get_input('x2', 0);
$y2 = (int) get_input('y2', 0);

$master = elgg_get_entity_icon($owner, 'master');

// ensuring the avatar image exists in the first place
if (!$master->exists()) {
	register_error(elgg_echo('avatar:crop:fail'));
	forward(REFERER);
}

$created = elgg_create_entity_icons($owner, $master, $x1, $y1, $x2, $y2, false);
if (!$created) {
	register_error(elgg_echo('avatar:resize:fail'));
	forward(REFERER);
}

system_message(elgg_echo('avatar:crop:success'));
$view = 'river/user/default/profileiconupdate';
elgg_delete_river(array('subject_guid' => $owner->guid, 'view' => $view));
elgg_create_river_item(array(
	'view' => $view,
	'action_type' => 'update',
	'subject_guid' => $owner->guid,
	'object_guid' => $owner->guid,
));


forward(REFERER);
