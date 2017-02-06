<?php
/**
 * Provide a way of setting your full name.
 */
$user = elgg_get_logged_in_user_entity();
$form = elgg_view_form('usersettings/name', [], $vars);
echo elgg_view('core/settings/account/wrapper', [
	'title' => elgg_echo('user:name:label'),
	'intro' => $user->name,
	'content' => $form,
]);
