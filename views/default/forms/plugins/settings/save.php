<?php
/**
 * Used to show plugin settings for both users and admins.
 *
 * @package Elgg.Core
 * @subpackage Plugins
 */

$plugin = $vars['entity'];
$plugin_id = $plugin->getID();
$user_guid = elgg_extract('user_guid', $vars, elgg_get_logged_in_user_guid());

// Do we want to show admin settings or user settings
$type = elgg_extract('type', $vars, '');

if ($type != 'user') {
	$type = '';
}

if (elgg_view_exists("plugins/$plugin_id/{$type}settings")) {
	echo elgg_view("plugins/$plugin_id/{$type}settings", $vars);
} else {
	echo elgg_view_deprecated("{$type}settings/$plugin_id/edit", $vars, "Use the view plugins/$plugin_id/{$type}settings", 1.8);
}

echo '<div class="elgg-foot">';
echo elgg_view('input/hidden', array('name' => 'plugin_id', 'value' => $plugin_id));
echo elgg_view('input/hidden', array('name' => 'user_guid', 'value' => $user_guid));
echo elgg_view('input/submit', array('value' => elgg_echo('save')));
echo '</div>';
