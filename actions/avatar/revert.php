<?php
/**
 * Avatar revert action
 */

$guid = get_input('guid');
$user = get_entity($guid);
if ($user) {
	unset($user->icontime);
	system_message(elgg_echo('avatar:revert:success'));
} else {
	register_error(elgg_echo('avatar:revert:fail'));
}

forward(REFERER);
