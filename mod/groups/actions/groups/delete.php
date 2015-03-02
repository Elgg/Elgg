<?php
/**
 * Delete a group
 */
		
$guid = (int) get_input('guid');
if (!$guid) {
	// backward compatible
	elgg_deprecated_notice("Use 'guid' for group delete action", 1.8);
	$guid = (int)get_input('group_guid');
}
$entity = get_entity($guid);

if (!$entity->canEdit()) {
	register_error(elgg_echo('group:notdeleted'));
	forward(REFERER);
}

if (($entity) && ($entity instanceof ElggGroup)) {
	// delete group icons
	$owner_guid = $entity->owner_guid;
	$prefix = "groups/" . $entity->guid;
	$imagenames = elgg_get_config('icon_sizes');
	$img = new ElggFile();
	$img->owner_guid = $owner_guid;
	foreach ($imagenames as $name => $value) {
		$img->setFilename("{$prefix}{$name}.jpg");
		$img->delete();
	}
	
	// delete original icon
	$img->setFilename("{$prefix}.jpg");
	$img->delete();

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
