<?php
/**
 * Provide a way of setting your default access
 */

if (!elgg_get_config('allow_user_default_access')) {
	return;
}

$form = elgg_view_form('usersettings/default_access', [], $vars);

echo elgg_view('core/settings/account/wrapper', [
	'title' => elgg_echo('default_access:settings'),
	'intro' => elgg_echo('default_access:settings:intro'),
	'content' => $form,
]);
