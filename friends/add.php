<?php
/**
 * Elgg add a collection of friends
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

// You need to be logged in for this one
gatekeeper();

$title = elgg_echo('friends:collections:add');

$content = elgg_view_title($title);

$content .= elgg_view('friends/forms/edit', array(
	'friends' => get_user_friends(get_loggedin_userid(), "", 9999)
	)
);

$body = elgg_view_layout('one_column_with_sidebar', $content);

page_draw(elgg_echo('friends:collections:add'),$body);
