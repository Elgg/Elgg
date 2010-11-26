<?php
/**
 * Elgg friends page
 *
 * @package Elgg
 * @subpackage Core
 */

$owner = page_owner_entity();
if (!$owner) {
	gatekeeper();
	set_page_owner(get_loggedin_userid());
	$owner = page_owner_entity();
}

$title = sprintf(elgg_echo("friends:owned"), $owner->name);

$content = elgg_view_title($title);

$content .= list_entities_from_relationship('friend', $owner->getGUID(), FALSE, 'user', '', 0, 10, FALSE);

$body = elgg_view_layout('two_column_left_sidebar', '', $content);

page_draw($title, $body);
