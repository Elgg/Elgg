<?php
/**
 * Regenerate API tokens
 */

$guid = (int) get_input('guid');
if (empty($guid)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$entity = get_entity($guid);
if (!$entity instanceof ElggApiKey || !$entity->canEdit()) {
	return elgg_error_response(elgg_echo('actionunauthorized'));
}

if (!$entity->regenerateKeys()) {
	return elgg_error_response(elgg_echo('save:fail'));
}

return elgg_ok_response('', elgg_echo('webservices:action:api_key:regenerate:success'));
