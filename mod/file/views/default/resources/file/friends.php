<?php
/**
 * Friends Files
 */

use Elgg\Exceptions\Http\EntityNotFoundException;

$username = elgg_extract('username', $vars);
$owner = get_user_by_username($username);

if (!$owner) {
	throw new EntityNotFoundException();
}

elgg_push_collection_breadcrumbs('object', 'file', $owner, true);

elgg_register_title_button('file', 'add', 'object', 'file');

$params = $vars;
$params['entity'] = $owner;

echo elgg_view_page(elgg_echo('collection:object:file:friends'), [
	'filter_value' => 'friends',
	'content' => elgg_view('file/listing/friends', $params),
]);
