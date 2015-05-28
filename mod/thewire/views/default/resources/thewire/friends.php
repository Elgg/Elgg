<?php
/**
 * Wire posts of your friends
 */

$owner = elgg_get_page_owner_entity();
if (!$owner) {
	forward('', '404');
}

$title = elgg_echo('thewire:friends');

elgg_push_breadcrumb(elgg_echo('thewire'), "thewire/all");
elgg_push_breadcrumb($owner->name, "thewire/owner/$owner->username");
elgg_push_breadcrumb(elgg_echo('friends'));

if (elgg_get_logged_in_user_guid() == $owner->guid) {
	$form_vars = array('class' => 'thewire-form');
	$content = elgg_view_form('thewire/add', $form_vars);
	$content .= elgg_view('input/urlshortener');
}

$content .= elgg_list_entities_from_relationship(array(
	'type' => 'object',
	'subtype' => 'thewire',
	'full_view' => false,
	'relationship' => 'friend',
	'relationship_guid' => $owner->guid,
	'relationship_join_on' => 'container_guid',
	'preload_owners' => true,
));

$body = elgg_view_layout('content', array(
	'filter_context' => 'friends',
	'content' => $content,
	'title' => $title,
));

echo elgg_view_page($title, $body);
