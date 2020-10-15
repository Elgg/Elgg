<?php
/**
 * User's wire posts
 */

use Elgg\Exceptions\Http\EntityNotFoundException;

$username = elgg_extract('username', $vars);

$user = get_user_by_username($username);
if (!$user) {
	throw new EntityNotFoundException();
}

$title = elgg_echo('collection:object:thewire:owner', [$user->getDisplayName()]);

elgg_push_collection_breadcrumbs('object', 'thewire', $user);

$content = '';

if (elgg_get_logged_in_user_guid() == $user->guid) {
	$content .= elgg_view_form('thewire/add', [
		'class' => 'thewire-form',
	]);
	$content .= elgg_view('input/urlshortener');
}

$content .= elgg_list_entities([
	'type' => 'object',
	'subtype' => 'thewire',
	'owner_guid' => $user->guid,
	'limit' => get_input('limit', 15),
]);

echo elgg_view_page($title, [
	'content' => $content,
	'sidebar' => elgg_view('thewire/sidebar'),
	'filter_value' => $user->guid === elgg_get_logged_in_user_guid() ? 'mine' : 'none',
]);
