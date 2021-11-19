<?php
/**
 * Elgg user tools settings
 */

use Elgg\Exceptions\Http\PageNotFoundException;

$user = elgg_get_page_owner_entity();

$plugin_id = elgg_extract('plugin_id', $vars);

$plugin = elgg_get_plugin_from_id($plugin_id);
if (!$plugin instanceof \ElggPlugin) {
	throw new PageNotFoundException(elgg_echo('PluginException:InvalidID', [$plugin_id]));
}

if (elgg_language_key_exists($plugin_id . ':usersettings:title')) {
	$title = elgg_echo($plugin_id . ':usersettings:title');
} else {
	$title = $plugin->getDisplayName();
}

elgg_push_breadcrumb(elgg_echo('settings'), elgg_generate_url('settings:account', ['username' => $user->username]));

$form_vars = [];
if (elgg_action_exists("{$plugin_id}/usersettings/save")) {
	$form_vars['action'] = "action/{$plugin_id}/usersettings/save";
}

$content = elgg_view_form('plugins/usersettings/save', $form_vars, ['entity' => $plugin]);

echo elgg_view_page($title, [
	'content' => $content,
	'show_owner_block_menu' => false,
	'filter_id' => 'settings',
	'filter_value' => 'plugin_settings',
]);
