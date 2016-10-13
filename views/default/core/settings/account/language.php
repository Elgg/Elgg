<?php
/**
 * Provide a way of setting your language prefs
 */

$form = elgg_view_form('usersettings/language', [], $vars);

echo elgg_view('core/settings/account/wrapper', [
	'title' => elgg_echo('user:set:language'),
	'intro' => elgg_echo('user:language:intro'),
	'content' => $form,
]);
