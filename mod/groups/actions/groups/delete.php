<?php

/**
 * Delete a group
 *
 * @todo: Deprecate and use entity/delete action instead
 */
$guid = (int) get_input('guid');
elgg_entity_gatekeeper($guid, 'group');

$entity = get_entity($guid);

if (!$entity->canDelete()) {
	register_error(elgg_echo('group:notdeleted'));
	forward(REFERER);
}

// delete group
if ($entity->delete()) {
	system_message(elgg_echo('group:deleted'));
} else {
	register_error(elgg_echo('group:notdeleted'));
}

$url_name = elgg_get_logged_in_user_entity()->username;
forward(elgg_get_site_url() . "groups/member/{$url_name}");
