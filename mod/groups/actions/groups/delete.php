<?php

/**
 * Delete a group
 *
 * @todo: Deprecate and use entity/delete action instead
 */
$guid = (int) get_input('guid');
elgg_entity_gatekeeper($guid, 'group');

$entity = get_entity($guid);

if (!$entity->delete()) {
	return elgg_error_response(elgg_echo('groups:notdeleted'));
}

$user = elgg_get_logged_in_user_entity();
return elgg_ok_response('', elgg_echo('groups:deleted'), "groups/member/{$user->username}");
