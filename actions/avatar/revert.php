<?php
/**
 * Avatar revert action
 */

$guid = get_input('guid');
$user = get_entity($guid);
if ($user) {
	// Delete all icons from diskspace
	$icon_sizes = elgg_get_config('icon_sizes');
	foreach ($icon_sizes as $name => $size_info) {
		$file = new ElggFile();
		$file->owner_guid = $guid;
		$file->setFilename("profile/{$guid}{$name}.jpg");
		$filepath = $file->getFilenameOnFilestore();
		if (!$file->delete()) {
			elgg_log("Avatar file revert failed. Remove $filepath manually, please.", 'WARNING');
		}
	}
	
	// Revert crop coords
	unset($user->x1);
	unset($user->x2);
	unset($user->y1);
	unset($user->y2);
	
	// Revert icon
	unset($user->icontime);
	system_message(elgg_echo('avatar:revert:success'));
} else {
	register_error(elgg_echo('avatar:revert:fail'));
}

forward(REFERER);
