<?php
/**
 * Elgg add a collection of friends
 *
 * @package Elgg.Core
 * @subpackage Social.Collections
 */

// You need to be logged in for this one
elgg_gatekeeper();

$title = elgg_echo('friends:collections:add');

$content = elgg_view_form('friends/collections/add', array(), array(
	'friends' => elgg_get_logged_in_user_entity()->getFriends(array('limit' => 0)),
));

$body = elgg_view_layout('one_sidebar', array(
	'title' => $title,
	'content' => $content
));

echo elgg_view_page($title, $body);
