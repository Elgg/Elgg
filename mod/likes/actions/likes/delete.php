<?php
/**
 * Elgg delete like action
 *
 */

// Support deleting by id in case we're deleting another user's likes
$id = (int) get_input('id');

$like = NULL;
if ($id) {
	$like = elgg_get_annotation_from_id($id);
}

if (!$like) {
	$likes = elgg_get_annotations(array(
		'guid' => (int) get_input('guid'),
		'annotation_owner_guid' => elgg_get_logged_in_user_guid(),
		'annotation_name' => 'likes',
	));
	$like = $likes[0];
}

if ($like && $like->canEdit()) {
	$entity = $like->getEntity();
	$like->delete();
	system_message(elgg_echo("likes:deleted"));

	if ($entity && elgg_is_xhr()) {
		$num_of_likes = likes_count($entity);
		if ($num_of_likes == 1) {
			$likes_string = elgg_echo('likes:userlikedthis', array($num_of_likes));
		} else {
			$likes_string = elgg_echo('likes:userslikedthis', array($num_of_likes));
		}
		echo json_encode([
			'text' => $likes_string,
			'selector' => "[data-likes-guid={$entity->guid}]",
			'num_likes' => $num_of_likes,
		]);
	}

	forward(REFERER);
}

register_error(elgg_echo("likes:notdeleted"));
forward(REFERER);
