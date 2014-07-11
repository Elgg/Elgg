<?php
/**
 * Elgg collection add page
 *
 * @package Elgg.Core
 * @subpackage Friends.Collections
 */

$collection_name = htmlspecialchars(get_input('collection_name', '', false), ENT_QUOTES, 'UTF-8');
$friends = get_input('friends_collection');

if (!$collection_name) {
	register_error(elgg_echo("friends:nocollectionname"));
	forward(REFERER);
}

$id = create_access_collection($collection_name);

if ($id) {
	$result = update_access_collection($id, $friends);
	if ($result) {
		system_message(elgg_echo("friends:collectionadded"));
		forward("collections/owner/" . elgg_get_logged_in_user_entity()->username);
	} else {
		register_error(elgg_echo("friends:nocollectionname"));
		forward(REFERER);
	}
} else {
	register_error(elgg_echo("friends:nocollectionname"));
	forward(REFERER);
}