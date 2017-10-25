<?php
/**
 * Avatar upload action
 */

$guid = (int) get_input('guid');
$owner = get_user($guid);

if (!$owner || !$owner->canEdit()) {
	return elgg_error_response(elgg_echo('avatar:upload:fail'));
}

$error = elgg_get_friendly_upload_error($_FILES['avatar']['error']);
if ($error) {
	return elgg_error_response($error);
}

if (!$owner->saveIconFromUploadedFile('avatar')) {
	return elgg_error_response(elgg_echo('avatar:resize:fail'));
}

if (!elgg_trigger_event('profileiconupdate', $owner->type, $owner)) {
	return elgg_error_response();
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

return elgg_ok_response('', elgg_echo('avatar:upload:success'));
