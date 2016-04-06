<?php
/**
 * Elgg collections of friends
 *
 * @package Elgg.Core
 * @subpackage Social.Collections
 */

$owner = elgg_get_logged_in_user_entity();
if (!$owner) {
	forward('', '404');
}

$title = elgg_echo('friends:collections');
if ($owner->canEdit()) {
	elgg_register_menu_item('title', [
		'name' => 'add',
		'href' => "collections/add/$owner->guid",
		'text' => elgg_echo('collections:add'),
		'link_class' => 'elgg-button elgg-button-action',
	]);
}

$content = elgg_view_access_collections($owner->guid);

$body = elgg_view_layout('content', array(
	'filter' => false,
	'content' => $content,
	'title' => $title,
	'context' => 'collections',
));

echo elgg_view_page($title, $body);
