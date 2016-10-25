<?php
/**
 * Provide a way of setting your email
 */

$form = elgg_view_form('usersettings/email', [], $vars);

echo elgg_view('core/settings/account/wrapper', [
	'title' => elgg_echo('email:settings'),
	'intro' => elgg_echo('email:settings:intro'),
	'content' => $form,
]);
