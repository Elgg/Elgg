<?php
/**
 * Elgg collection add page
 *
 * @package Elgg.Core
 * @subpackage Friends.Collections
 */

$collection_name = get_input('collection_name');
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
		// go to the collections page
		forward("pg/collections/" . get_loggedin_user()->username);
	} else {
		register_error(elgg_echo("friends:nocollectionname"));
		forward(REFERER);
	}
} else {
	register_error(elgg_echo("friends:nocollectionname"));
	forward(REFERER);
}