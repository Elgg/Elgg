<?php
/**
 * Provide a way of setting your email
 */
$user = elgg_get_logged_in_user_entity();
$form = elgg_view_form('usersettings/email', [], $vars);
echo elgg_view('core/settings/account/wrapper', [
	'title' => elgg_echo('email:settings'),
	'intro' => $user->email,
	'content' => $form,
]);