<?php
/**
 * Elgg delete like action
 *
 */

$likes = elgg_get_annotations([
	'guid' => (int) get_input('guid'),
	'annotation_owner_guid' => elgg_get_logged_in_user_guid(),
	'annotation_name' => 'likes',
]);
$like = elgg_extract(0, $likes);

if (!$like || !$like->delete()) {
	return elgg_error_response(elgg_echo('likes:notdeleted'));
}

return elgg_ok_response('', elgg_echo('likes:deleted'));
