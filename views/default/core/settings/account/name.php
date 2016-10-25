<?php
/**
 * Provide a way of setting your full name.
 */

$form = elgg_view_form('usersettings/name', [], $vars);

echo elgg_view('core/settings/account/wrapper', [
	'title' => elgg_echo('user:name:label'),
	'intro' => elgg_echo('user:name:intro'),
	'content' => $form,
]);
