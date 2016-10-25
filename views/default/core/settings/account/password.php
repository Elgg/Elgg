<?php
/**
 * Provide a way of setting your password
 */

$form = elgg_view_form('usersettings/password', [], $vars);

echo elgg_view('core/settings/account/wrapper', [
	'title' => elgg_echo('user:set:password'),
	'intro' => elgg_echo('user:password:intro'),
	'content' => $form,
]);
