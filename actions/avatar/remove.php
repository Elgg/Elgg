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

$user->deleteIcon();

system_message(elgg_echo('avatar:remove:success'));
forward(REFERER);
