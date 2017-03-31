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

if ($like && $like->canEdit()) {
	$entity = $like->getEntity();
	$like->delete();
	system_message(elgg_echo("likes:deleted"));

	if ($entity && elgg_is_xhr()) {
		$num_of_likes = likes_count($entity);
		echo json_encode([
			'text' => $num_of_likes,
			'selector' => ".elgg-counter[data-channel=\"likes:{$entity->guid}\"]",
			'num_likes' => $num_of_likes,
		]);
	}

	forward(REFERER);
}

register_error(elgg_echo("likes:notdeleted"));
forward(REFERER);
