<?php
/**
 * Used to show plugin settings for both users and admins.
 *
 * @package Elgg.Core
 * @subpackage Plugins
 */

$plugin = elgg_extract('entity', $vars);
$plugin_id = $plugin->getID();
$user_guid = elgg_extract('user_guid', $vars, elgg_get_logged_in_user_guid());

// Do we want to show admin settings or user settings
$type = elgg_extract('type', $vars, '');

if ($type != 'user') {
	$type = '';
}

if (elgg_view_exists("plugins/$plugin_id/{$type}settings")) {
	echo elgg_view("plugins/$plugin_id/{$type}settings", $vars);
}

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'plugin_id',
	'value' => $plugin_id,
]);

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'user_guid',
	'value' => $user_guid,
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
