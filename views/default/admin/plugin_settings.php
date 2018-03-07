<?php
/**
 * Elgg plugin settings
 *
 * @uses ElggPlugin $vars['entity'] The plugin object to display settings for.
 * @uses ElggPlugin $vars['plugin'] Same as entity required for plugin settings backward compatibility
 *
 * @package Elgg.Core
 * @subpackage Plugins.Settings
 */

$plugin = elgg_extract('entity', $vars);
$plugin_id = $plugin->getID();

if (!elgg_view_exists("plugins/{$plugin_id}/settings")) {
	return;
}

$form_vars = [
	'id' => "{$plugin_id}-settings",
	'class' => 'elgg-form-settings',
];

if (elgg_action_exists("{$plugin_id}/settings/save")) {
	$form_vars['action'] = "action/{$plugin_id}/settings/save";
}

echo elgg_view_form('plugins/settings/save', $form_vars, $vars);
