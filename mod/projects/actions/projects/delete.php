<?php
/**
 * Delete a project
 */
		
$guid = (int) get_input('guid');
if (!$guid) {
	// backward compatible
	elgg_deprecated_notice("Use 'guid' for project delete action", 1.8);
	$guid = (int)get_input('project_guid');
}
$entity = get_entity($guid);

if (!$entity->canEdit()) {
	register_error(elgg_echo('project:notdeleted'));
	forward(REFERER);
}

if (($entity) && ($entity instanceof ElggGroup)) {
	// delete project icons
	$owner_guid = $entity->owner_guid;
	$prefix = "projects/" . $entity->guid;
	$imagenames = array('.jpg', 'tiny.jpg', 'small.jpg', 'medium.jpg', 'large.jpg');
	$img = new ElggFile();
	$img->owner_guid = $owner_guid;
	foreach ($imagenames as $name) {
		$img->setFilename($prefix . $name);
		$img->delete();
	}

	// delete project
	if ($entity->delete()) {
		system_message(elgg_echo('project:deleted'));
	} else {
		register_error(elgg_echo('project:notdeleted'));
	}
} else {
	register_error(elgg_echo('project:notdeleted'));
}

$url_name = elgg_get_logged_in_user_entity()->username;
forward(elgg_get_site_url() . "projects/member/{$url_name}");
