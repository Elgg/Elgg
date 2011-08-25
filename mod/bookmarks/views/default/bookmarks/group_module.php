<?php
/**
 * List most recent bookmarks on group profile page
 *
 * @package Bookmarks
 */

$group = elgg_get_page_owner_entity();

if ($group->bookmarks_enable == "no") {
	return true;
}

$all_link = elgg_view('output/url', array(
	'href' => "bookmarks/group/$group->guid/all",
	'text' => elgg_echo('link:view:all'),
));

elgg_push_context('widgets');
$options = array(
	'type' => 'object',
	'subtype' => 'bookmarks',
	'container_guid' => elgg_get_page_owner_guid(),
	'limit' => 6,
	'full_view' => false,
	'pagination' => false,
);
$content = elgg_list_entities($options);
elgg_pop_context();

if (!$content) {
	$content = '<p>' . elgg_echo('bookmarks:none') . '</p>';
}

$new_link = elgg_view('output/url', array(
	'href' => "bookmarks/add/$group->guid",
	'text' => elgg_echo('bookmarks:add'),
));

echo elgg_view('groups/profile/module', array(
	'title' => elgg_echo('bookmarks:group'),
	'content' => $content,
	'all_link' => $all_link,
	'add_link' => $new_link,
));
