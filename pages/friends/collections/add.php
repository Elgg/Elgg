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

$form_body = elgg_view('forms/friends/collections/edit', array(
	'friends' => get_user_friends(elgg_get_logged_in_user_guid(), "", 9999)
));
$content .= elgg_view('input/form', array(
	'action' => 'action/friends/collections/add',
	'body' => $form_body,
));

$body = elgg_view_layout('one_sidebar', array('content' => $content));

echo elgg_view_page(elgg_echo('friends:collections:add'), $body);
