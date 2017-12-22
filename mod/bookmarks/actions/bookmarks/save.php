<?php
/**
* Elgg bookmarks save action
*
* @package Bookmarks
*/

$title = elgg_get_title_input();
$description = get_input('description');
$address = get_input('address');
$access_id = get_input('access_id');
$tags = get_input('tags');
$guid = get_input('guid');
$container_guid = get_input('container_guid', elgg_get_logged_in_user_guid());

elgg_make_sticky_form('bookmarks');

// don't use elgg_normalize_url() because we don't want
// relative links resolved to this site.
if ($address && !preg_match("#^((ht|f)tps?:)?//#i", $address)) {
	$address = "http://$address";
}

if (!$title || !$address) {
	return elgg_error_response(elgg_echo('bookmarks:save:failed'));
}

if (!filter_var($address, FILTER_VALIDATE_URL)) {
	return elgg_error_response(elgg_echo('bookmarks:save:failed'));
}

if ($guid == 0) {
	$bookmark = new ElggBookmark;
	$bookmark->container_guid = (int) get_input('container_guid', elgg_get_logged_in_user_guid());
	$new = true;
} else {
	$bookmark = get_entity($guid);
	if (!$bookmark instanceof ElggBookmark || !$bookmark->canEdit()) {
		return elgg_error_response(elgg_echo('bookmarks:save:failed'));
	}
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
		'object_guid' => $bookmark->getGUID(),
	]);
}

return elgg_ok_response('', elgg_echo('bookmarks:save:success'), $bookmark->getURL());
