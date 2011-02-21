<?php
/**
 * Elgg user tools settings
 *
 * @package Elgg
 * @subpackage Core
 */

// Make sure only valid users can see this
gatekeeper();

// Make sure we don't open a security hole ...
if ((!elgg_get_page_owner_entity()) || (!elgg_get_page_owner_entity()->canEdit())) {
	elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());
}

$title = elgg_echo("usersettings:plugins");

$content = elgg_view("core/settings/tools",
	array('installed_plugins' => elgg_get_plugins()));

$params = array(
	'content' => $content,
	'title' => $title,
);
$body = elgg_view_layout('one_sidebar', $params);

echo elgg_view_page($title, $body);
