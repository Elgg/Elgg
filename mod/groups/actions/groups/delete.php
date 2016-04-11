<?php
/**
 * Delete a group
 */
		
$guid = (int) get_input('guid');
$entity = get_entity($guid);

if (!$entity->canEdit()) {
	register_error(elgg_echo('group:notdeleted'));
	forward(REFERER);
}

if (($entity) && ($entity instanceof ElggGroup)) {
	// delete group icons
	$owner_guid = $entity->owner_guid;
	$prefix = "groups/" . $entity->guid;

	elgg_clear_entity_icons($entity);

	// delete group
	if ($entity->delete()) {
		system_message(elgg_echo('group:deleted'));
	} else {
		register_error(elgg_echo('group:notdeleted'));
	}
} else {
	register_error(elgg_echo('group:notdeleted'));
}

$url_name = elgg_get_logged_in_user_entity()->username;
forward(elgg_get_site_url() . "groups/member/{$url_name}");
