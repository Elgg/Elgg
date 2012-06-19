<?php
/**
 * Elgg likes button
 *
 * @uses $vars['entity']
 */

if (!isset($vars['entity'])) {
	return true;
}

$guid = $vars['entity']->getGUID();

// check to see if the user has already liked this
if (elgg_is_logged_in() && $vars['entity']->canAnnotate(0, 'likes')) {
	if (!elgg_annotation_exists($guid, 'likes')) {
		$url = elgg_get_site_url() . "action/likes/add?guid={$guid}";
		$params = array(
			'href' => $url,
			'text' => elgg_view_icon('thumbs-up'),
			'title' => elgg_echo('likes:likethis'),
			'is_action' => true,
			'is_trusted' => true,
		);
		$likes_button = elgg_view('output/url', $params);
	} else {
		$like = elgg_get_annotations(array(
			'guid' => $guid,
			'annotation_owner_guid' => elgg_get_logged_in_user_guid(),
			'annotation_name' => 'likes',
		));
		$like = $like[0];
		$url = elgg_get_site_url() . "action/likes/delete?id={$like->id}";
		$params = array(
			'href' => $url,
			'text' => elgg_view_icon('thumbs-up-alt'),
			'title' => elgg_echo('likes:remove'),
			'is_action' => true,
			'is_trusted' => true,
		);
		$likes_button = elgg_view('output/url', $params);
	}
}

echo $likes_button;
