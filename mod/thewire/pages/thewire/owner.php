<?php
/**
 * User's wire posts
 * 
 */

$owner = elgg_get_page_owner_entity();

$title = elgg_echo('thewire:user', array($owner->name));

elgg_push_breadcrumb(elgg_echo('thewire'), "thewire/all");
elgg_push_breadcrumb($owner->name);

if (get_loggedin_userid() == $owner->guid) {
	$content = elgg_view_form('thewire/add');
	$content .= elgg_view('input/urlshortener');
}

$content .= elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'thewire',
	'owner_guid' => $owner->guid,
	'limit' => 15,
));

$body = elgg_view_layout('content', array(
	'filter_context' => 'mine',
	'content' => $content,
	'title' => $title,
	'buttons' => false,
	'sidebar' => elgg_view('thewire/sidebar'),
));

echo elgg_view_page($title, $body);
