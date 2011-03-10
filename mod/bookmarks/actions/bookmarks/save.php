<?php
/**
* Elgg bookmarks save action
*
* @package Bookmarks
*/

gatekeeper();

$title = strip_tags(get_input('title'));
$description = get_input('description');
$address = get_input('address');
$access_id = get_input('access_id');
$tags = get_input('tags');
$guid = get_input('guid');
$share = get_input('share');
$container_guid = get_input('container_guid', elgg_get_logged_in_user_guid());

elgg_make_sticky_form('bookmarks');

// don't use elgg_normalize_url() because we don't want
// relative links resolved to this site.
if ($address && !preg_match("#^((ht|f)tps?:)?//#i", $address)) {
	$address = "http://$address";
}

if (!$title || !$address || !filter_var($address, FILTER_VALIDATE_URL)) {
	register_error(elgg_echo('bookmarks:save:failed'));
	forward(REFERER);
}

if ($guid == 0) {
	$bookmark = new ElggObject;
	$bookmark->subtype = "bookmarks";
	$bookmark->container_guid = (int)get_input('container_guid', $_SESSION['user']->getGUID());
	$new = true;
} else {
	$bookmark = get_entity($guid);
	if (!$bookmark->canEdit()) {
		system_message(elgg_echo('bookmarks:save:failed'));
		forward(REFERRER);
	}
}

$tagarray = string_to_tag_array($tags);

$bookmark->title = $title;
$bookmark->address = $address;
$bookmark->description = $description;
$bookmark->access_id = $access_id;
$bookmark->tags = $tagarray;

if ($bookmark->save()) {

	elgg_clear_sticky_form('bookmarks');

	// @todo
	if (is_array($shares) && sizeof($shares) > 0) {
		foreach($shares as $share) {
			$share = (int) $share;
			add_entity_relationship($bookmark->getGUID(), 'share', $share);
		}
	}
	system_message(elgg_echo('bookmarks:save:success'));

	//add to river only if new
	if ($new) {
		add_to_river('river/object/bookmarks/create','create', elgg_get_logged_in_user_guid(), $bookmark->getGUID());
	}

	forward($bookmark->getURL());
} else {
	register_error(elgg_echo('bookmarks:save:failed'));
	forward("bookmarks");
}
