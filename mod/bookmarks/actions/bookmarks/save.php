<?php
/**
* Elgg bookmarks save action
*/

$title = elgg_get_title_input();
$description = get_input('description');
$address = get_input('address');
$access_id = (int) get_input('access_id');
$tags = get_input('tags');
$guid = (int) get_input('guid');
$container_guid = (int) get_input('container_guid', elgg_get_logged_in_user_guid());

elgg_make_sticky_form('bookmarks');

// don't use elgg_normalize_url() because we don't want
// relative links resolved to this site.
if ($address && !preg_match("#^((ht|f)tps?:)?//#i", $address)) {
	$address = "http://{$address}";
}

if (empty($title) || empty($address)) {
	return elgg_error_response(elgg_echo('bookmarks:save:failed'));
}

if (!filter_var($address, FILTER_VALIDATE_URL)) {
	return elgg_error_response(elgg_echo('bookmarks:save:failed'));
}

$new = true;
if (empty($guid)) {
	$bookmark = new ElggBookmark;
	$bookmark->container_guid = $container_guid;
} else {
	$bookmark = get_entity($guid);
	if (!$bookmark instanceof ElggBookmark || !$bookmark->canEdit()) {
		return elgg_error_response(elgg_echo('bookmarks:save:failed'));
	}
	$new = false;
}

$bookmark->title = $title;
$bookmark->address = $address;
$bookmark->description = $description;
$bookmark->access_id = $access_id;
$bookmark->tags = string_to_tag_array($tags);

if (!$bookmark->save()) {
	return elgg_error_response(elgg_echo('bookmarks:save:failed'));
}

elgg_clear_sticky_form('bookmarks');

//add to river only if new
if ($new) {
	elgg_create_river_item([
		'view' => 'river/object/bookmarks/create',
		'action_type' => 'create',
		'object_guid' => $bookmark->guid,
	]);
}

return elgg_ok_response('', elgg_echo('bookmarks:save:success'), $bookmark->getURL());
