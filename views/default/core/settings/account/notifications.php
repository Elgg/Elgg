<?php
/**
 * User settings for notifications.
 */

$form = elgg_view_form('usersettings/notifications', [], $vars);

echo elgg_view('core/settings/account/wrapper', [
	'title' => elgg_echo('notifications:usersettings'),
	'intro' => elgg_echo('notifications:usersettings:intro'),
	'content' => $form,
]);
