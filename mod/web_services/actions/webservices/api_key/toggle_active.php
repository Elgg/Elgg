<?php

$guid = (int) get_input('guid');

$entity = get_entity($guid);
if (!$entity instanceof ElggApiKey) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

if (!$entity->canEdit()) {
	return elgg_error_response(elgg_echo('actionunauthorized'));
}

if ($entity->hasActiveKeys()) {
	// disable
	if ($entity->disableKeys()) {
		return elgg_ok_response('', elgg_echo('webservices:action:api_key:toggle_active:disable:success'));
	}
	
	return elgg_error_response(elgg_echo('webservices:action:api_key:toggle_active:disable:error'));
}

// enable
if ($entity->enableKeys()) {
	return elgg_ok_response('', elgg_echo('webservices:action:api_key:toggle_active:enable:success'));
}

return elgg_error_response(elgg_echo('webservices:action:api_key:toggle_active:enable:error'));
