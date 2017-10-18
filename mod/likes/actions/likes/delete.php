<?php
/**
 * Elgg delete like action
 *
 */

// Support deleting by id in case we're deleting another user's likes
$id = (int) get_input('id');

$like = null;
if ($id) {
	$like = elgg_get_annotation_from_id($id);
}

if (!$like) {
	$likes = elgg_get_annotations([
		'guid' => (int) get_input('guid'),
		'annotation_owner_guid' => elgg_get_logged_in_user_guid(),
		'annotation_name' => 'likes',
	]);
	$like = $likes[0];
}

if (!$like || !$like->delete()) {
	return elgg_error_response(elgg_echo('likes:notdeleted'));
}

return elgg_ok_response('', elgg_echo('likes:deleted'));
