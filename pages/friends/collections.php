<?php
/**
 * Elgg collections of friends
 *
 * @package Elgg
 * @subpackage Core
 */

// You need to be logged in for this one
gatekeeper();

$title = elgg_echo('friends:collections');

$content = elgg_view_title($title);

$content .= elgg_view_access_collections(elgg_get_logged_in_user_guid());

$body = elgg_view_layout('one_sidebar', array('content' => $content));

echo elgg_view_page($title, $body);
