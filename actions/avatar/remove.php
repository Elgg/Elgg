<?php
/**
 * Avatar remove action
 */

$user_guid = get_input('guid');
$user = get_user($user_guid);

if (!$user || !$user->canEdit()) {
	register_error(elgg_echo('avatar:remove:fail'));
	forward(REFERER);
}

// Delete all icons from diskspace
$icon_sizes = elgg_get_config('icon_sizes');
foreach ($icon_sizes as $name => $size_info) {
	$file = new ElggFile();
	$file->owner_guid = $user_guid;
	$file->setFilename("profile/{$user_guid}{$name}.jpg");
	$filepath = $file->getFilenameOnFilestore();
	if (!$file->delete()) {
		elgg_log("Avatar file remove failed. Remove $filepath manually, please.", 'WARNING');
	}
}

// Remove crop coords
unset($user->x1);
unset($user->x2);
unset($user->y1);
unset($user->y2);

// Remove icon
unset($user->icontime);

system_message(elgg_echo('avatar:remove:success'));
forward(REFERER);
