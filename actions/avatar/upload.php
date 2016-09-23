<?php
/**
 * Avatar upload action
 */

$guid = get_input('guid');
$owner = get_entity($guid);

if (!$owner || !($owner instanceof ElggUser) || !$owner->canEdit()) {
	register_error(elgg_echo('avatar:upload:fail'));
	forward(REFERER);
}

$error = elgg_get_friendly_upload_error($_FILES['avatar']['error']);
if ($error) {
	register_error($error);
	forward(REFERER);
}

if (!$owner->saveIconFromUploadedFile('avatar')) {
	register_error(elgg_echo('avatar:resize:fail'));
	forward(REFERER);
}

if (elgg_trigger_event('profileiconupdate', $owner->type, $owner)) {
	system_message(elgg_echo("avatar:upload:success"));

	$view = 'river/user/default/profileiconupdate';
	_elgg_delete_river(array('subject_guid' => $owner->guid, 'view' => $view));
	elgg_create_river_item(array(
		'view' => $view,
		'action_type' => 'update',
		'subject_guid' => $owner->guid,
		'object_guid' => $owner->guid,
	));
}

forward(REFERER);
