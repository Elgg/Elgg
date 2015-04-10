<?php
/**
 * Elgg user tools settings
 *
 * @package Elgg
 * @subpackage Core
 */

// Only logged in users
elgg_gatekeeper();

// Make sure we don't open a security hole ...
if ((!elgg_get_page_owner_entity()) || (!elgg_get_page_owner_entity()->canEdit())) {
	register_error(elgg_echo('noaccess'));
	forward('/');
}

$plugin_id = get_input("plugin_id");

if (empty($plugin_id)) {
	register_error(elgg_echo('ElggPlugin:MissingID'));
	forward(REFERER);
}

$plugin = elgg_get_plugin_from_id($plugin_id);

if (!$plugin) {
	register_error(elgg_echo('PluginException:InvalidID', array($plugin_id)));
	forward(REFERER);
}

if (elgg_language_key_exists($plugin_id . ':usersettings:title')) {
	$title = elgg_echo($plugin_id . ':usersettings:title');
} else {
	$title = $plugin->getManifest()->getName();
}

$content = elgg_view_form('plugins/usersettings/save', array(), array('entity' => $plugin));

$params = array(
	'content' => $content,
	'title' => $title,
);
$body = elgg_view_layout('one_sidebar', $params);

echo elgg_view_page($title, $body);
