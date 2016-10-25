<?php
/**
 * Provide a way of setting your username
 */

if (!elgg_is_admin_logged_in()) {
	// only admins are allowed to change the username
	return;
}

$form = elgg_view_form('usersettings/username', [], $vars);

echo elgg_view('core/settings/account/wrapper', [
	'title' => elgg_echo('username'),
	'intro' => elgg_echo('user:username:intro'),
	'content' => $form,
]);
