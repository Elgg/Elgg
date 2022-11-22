<?php
/**
 * Friends Files
 */

use Elgg\Exceptions\Http\EntityNotFoundException;

$username = (string) elgg_extract('username', $vars);

$owner = elgg_get_user_by_username($username);
if (!$owner instanceof \ElggUser) {
	throw new EntityNotFoundException();
}

elgg_push_collection_breadcrumbs('object', 'file', $owner, true);

elgg_register_title_button('add', 'object', 'file');

$params = $vars;
$params['entity'] = $owner;

echo elgg_view_page(elgg_echo('collection:object:file:friends'), [
	'filter_value' => 'friends',
	'content' => elgg_view('file/listing/friends', $params),
]);
