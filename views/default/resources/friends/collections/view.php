<?php
/**
 * Elgg collections of friends
 *
 * @package Elgg.Core
 * @subpackage Social.Collections
 */

$title = elgg_echo('friends:collections');
elgg_register_title_button('collections', 'add');

$content = elgg_view_access_collections(elgg_get_logged_in_user_guid());

$body = elgg_view_layout('content', array(
	'filter' => false,
	'content' => $content,
	'title' => $title,
	'context' => 'collections',
));

echo elgg_view_page($title, $body);
