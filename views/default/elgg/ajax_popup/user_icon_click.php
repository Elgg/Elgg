<?php

$ajax_data = $vars['ajax_data'];

$user = get_user($ajax_data['guid']);
if (!$user) {
	return;
}

echo elgg_view_menu('user_hover', [
	'entity' => $user,
	'username' => $user->username,
	'name' => $user->name,
	'class' => 'elgg-menu-hover',
]);
