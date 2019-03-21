<?php
/**
 * Add bookmark page
 *
 * @package Bookmarks
 */

elgg_gatekeeper();
elgg_group_gatekeeper();

$page_owner = elgg_get_page_owner_entity();

// Make sure user has permissions to add to container
if (!$page_owner || !$page_owner->canWriteToContainer(0, 'object', 'bookmarks')) {
	register_error(elgg_echo('actionunauthorized'));
	forward(REFERER);
}

$title = elgg_echo('bookmarks:add');
elgg_push_breadcrumb($title);

$vars = bookmarks_prepare_form_vars();
$content = elgg_view_form('bookmarks/save', array(), $vars);

$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
));

echo elgg_view_page($title, $body);