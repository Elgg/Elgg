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

elgg_clear_entity_icons($user);

system_message(elgg_echo('avatar:remove:success'));
forward(REFERER);
