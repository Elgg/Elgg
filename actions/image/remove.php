<?php
/**
 * Image remove action
 */

$guid = get_input('guid');
$entity = get_entity($guid);

if (!$entity || !$entity->canEdit()) {
	register_error(elgg_echo('image:remove:fail'));
	forward(REFERER);
}

// Delete all icons from diskspace
$icon_sizes = elgg_get_config('icon_sizes');
foreach ($icon_sizes as $name => $size_info) {
	$file = new ElggFile();
	$file->owner_guid = $guid;
	$file->setFilename("icon/{$name}.jpg");
	$filepath = $file->getFilenameOnFilestore();
	if (!$file->delete()) {
		elgg_log("Image file remove failed. Remove $filepath manually.", 'WARNING');
	}
}

// Remove crop coords
unset($entity->x1);
unset($entity->x2);
unset($entity->y1);
unset($entity->y2);

// Remove icon
unset($entity->icontime);

system_message(elgg_echo('image:remove:success'));
forward(REFERER);
