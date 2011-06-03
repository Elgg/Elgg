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

$header = "<span class=\"groups-widget-viewall\">$all_link</span>";
$header .= '<h3>' . elgg_echo('bookmarks:group') . '</h3>';

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

if ($group->canWriteToContainer()) {
	$new_link = elgg_view('output/url', array(
		'href' => "bookmarks/add/$group->guid",
		'text' => elgg_echo('bookmarks:add'),
	));
	$content .= "<span class='elgg-widget-more'>$new_link</span>";
}

echo elgg_view_module('info', '', $content, array('header' => $header));
