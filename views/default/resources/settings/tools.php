<?php
/**
 * Elgg user tools settings
 *
 * @package Elgg
 * @subpackage Core
 */

elgg_gatekeeper();

$username = elgg_extract('username', $vars);
if (!$username) {
	$username = elgg_get_logged_in_user_entity()->username;
}

$user = get_user_by_username($username);
if (!$user || !$user->canEdit()) {
	throw new \Elgg\EntityPermissionsException();
}

elgg_set_page_owner_guid($user->guid);

$plugin_id = elgg_extract("plugin_id", $vars);

if (empty($plugin_id)) {
	throw new \Elgg\PageNotFoundException(elgg_echo('ElggPlugin:MissingID'));
}

$plugin = elgg_get_plugin_from_id($plugin_id);

if (!$plugin) {
	throw new \Elgg\PageNotFoundException(elgg_echo('PluginException:InvalidID', [$plugin_id]));
}

if (elgg_language_key_exists($plugin_id . ':usersettings:title')) {
	$title = elgg_echo($plugin_id . ':usersettings:title');
} else {
	$title = $plugin->getDisplayName();
}

$username = elgg_extract('username', $vars);

elgg_push_breadcrumb(elgg_echo('settings'), "settings/user/$username");
elgg_push_breadcrumb(elgg_echo('usersettings:plugins:opt:linktext'));

$form_vars = [];

if (elgg_action_exists("{$plugin->getID()}/usersettings/save")) {
	$form_vars['action'] = "action/{$plugin->getID()}/usersettings/save";
}

$content = elgg_view_form('plugins/usersettings/save', $form_vars, ['entity' => $plugin]);

$params = [
	'content' => $content,
	'title' => $title,
	'show_owner_block_menu' => false,
];
$body = elgg_view_layout('one_sidebar', $params);

echo elgg_view_page($title, $body);
