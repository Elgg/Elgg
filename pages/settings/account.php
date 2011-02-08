<?php
/**
 * Elgg user account settings.
 *
 * @package Elgg
 * @subpackage Core
 */

// Only logged in users
gatekeeper();

// Make sure we don't open a security hole ...
if ((!elgg_get_page_owner_entity()) || (!elgg_get_page_owner_entity()->canEdit())) {
	set_page_owner(elgg_get_logged_in_user_guid());
}

$title = elgg_echo('usersettings:user');

$content .= elgg_view('core/settings/account');

$params = array(
	'content' => $content,
	'title' => $title,
);
$body = elgg_view_layout('one_sidebar', $params);

echo elgg_view_page($title, $body);
