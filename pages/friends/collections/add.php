<?php
/**
 * Elgg add a collection of friends
 *
 * @package Elgg
 * @subpackage Core
 */

// You need to be logged in for this one
gatekeeper();

$title = elgg_echo('friends:collections:add');

$content = elgg_view_title($title);

$content .= elgg_view_form('friends/collections/add', array(), array(
	'friends' => get_user_friends(elgg_get_logged_in_user_guid(), "", 9999),
));

$body = elgg_view_layout('one_sidebar', array('content' => $content));

echo elgg_view_page(elgg_echo('friends:collections:add'), $body);
