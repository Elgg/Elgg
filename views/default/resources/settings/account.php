<?php
/**
 * Elgg user account settings.
 *
 * @package Elgg
 * @subpackage Core
 */

// Only logged in users
elgg_gatekeeper();

// Make sure we don't open a security hole ...
$user = elgg_get_page_owner_entity();
if (!$user || !$user->canEdit()) {
	register_error(elgg_echo('noaccess'));
	forward('/');
}

$username = elgg_extract('username', $vars);

elgg_push_breadcrumb(elgg_echo('settings'), "settings/user/$username");

$title = elgg_echo('usersettings:user', array($user->name));

$content = elgg_view('core/settings/account', ['entity' => $user]);

$params = array(
	'content' => $content,
	'title' => $title,
);
$body = elgg_view_layout('one_sidebar', $params);

echo elgg_view_page($title, $body);
