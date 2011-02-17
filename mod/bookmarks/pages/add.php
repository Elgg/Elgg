<?php
/**
 * Add bookmark page
 *
 * @package Bookmarks
 */

$bookmark_guid = get_input('guid');
$bookmark = get_entity($bookmark_guid);
$container_guid = (int) get_input('container_guid');
$container = get_entity($container_guid);

// for groups.
$page_owner = $container;
if (elgg_instanceof($container, 'object')) {
	$page_owner = $container->getContainerEntity();
}

elgg_set_page_owner_guid($page_owner->getGUID());

$title = elgg_echo('bookmarks:add');
elgg_push_breadcrumb($title);

$vars = bookmarks_prepare_form_vars();
$content = elgg_view_form('bookmarks/save', array(), $vars);

$body = elgg_view_layout('content', array(
	'filter' => '',
	'buttons' => '',
	'content' => $content,
	'title' => $title,
));

echo elgg_view_page($title, $body);