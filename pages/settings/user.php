<?php
/**
 * Elgg user account settings.
 *
 * @package Elgg
 * @subpackage Core
 */

// Make sure only valid admin users can see this
gatekeeper();

// Make sure we don't open a security hole ...
if ((!elgg_get_page_owner()) || (!elgg_get_page_owner()->canEdit())) {
	set_page_owner(get_loggedin_userid());
}

$content = elgg_view_title(elgg_echo('usersettings:user'));
$content .= elgg_view("usersettings/form");

$body = elgg_view_layout("one_column_with_sidebar", array('content' => $content));

echo elgg_view_page(elgg_echo("usersettings:user"), $body);
