<?php
/**
 * Elgg plugin settings
 *
 * @uses ElggPlugin $vars['plugin'] The plugin object to display settings for.
 *
 * @package Elgg.Core
 * @subpackage Plugins.Settings
 */

$plugin = elgg_extract('plugin', $vars);
$plugin_id = $plugin->getID();

if (!elgg_view_exists("plugins/$plugin_id/settings")) {
	return;
}

// required for plugin settings backward compatibility
$vars['entity'] = $plugin;

$form_vars = [
	'id' => "$plugin_id-settings",
	'class' => 'elgg-form-settings',
];

if (elgg_action_exists("$plugin_id/settings/save")) {
	$form_vars['action'] = "$plugin_id/settings/save";
}

$body = elgg_view_form('plugins/settings/save', $form_vars, $vars);

echo elgg_view_module('info', $plugin->getDisplayName(), $body);
