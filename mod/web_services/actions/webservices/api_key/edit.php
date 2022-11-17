<?php
/**
 * Save action for API key objects
 */

$guid = (int) get_input('guid');
$title = elgg_get_title_input();

if (empty($title)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

if (!empty($guid)) {
	$entity = get_entity($guid);
	if (!$entity instanceof ElggApiKey || !$entity->canEdit()) {
		return elgg_error_response(elgg_echo('actionunauthorized'));
	}
} else {
	$entity = new ElggApiKey();
}

$entity->title = $title;
$entity->description = get_input('description');

if (!$entity->save()) {
	return elgg_error_response(elgg_echo('save:fail'));
}

return elgg_ok_response('', elgg_echo('webservices:action:api_key:edit:success'), 'admin/configure_utilities/ws_tokens');
