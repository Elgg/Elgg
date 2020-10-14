<?php
/**
 * Wire posts of your friends
 */
$username = elgg_extract('username', $vars);

$owner = get_user_by_username($username);
if (!$owner) {
	throw new \Elgg\EntityNotFoundException();
}

$title = elgg_echo('collection:object:thewire:friends');

elgg_push_collection_breadcrumbs('object', 'thewire', $owner, true);

$content = '';
if (elgg_get_logged_in_user_guid() == $owner->guid) {
	$content .= elgg_view_form('thewire/add', [
		'class' => 'thewire-form',
		'prevent_double_submit' => true,
	]);
	$content .= elgg_view('input/urlshortener');
}

$content .= elgg_list_entities([
	'type' => 'object',
	'subtype' => 'thewire',
	'relationship' => 'friend',
	'relationship_guid' => $owner->guid,
	'relationship_join_on' => 'container_guid',
]);

echo elgg_view_page($title, [
	'content' => $content,
	'filter_value' => $owner->guid === elgg_get_logged_in_user_guid() ? 'friends' : 'none',
]);
