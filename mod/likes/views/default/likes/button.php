<?php
/**
 * Elgg likes button
 *
 * @uses $vars['entity']
 */

if (!isset($vars['entity'])) {
	return true;
}

$entity = $vars['entity'];
/* @var ElggEntity $entity */

$guid = $entity->getGUID();

// check to see if the user has already liked this
if (elgg_is_logged_in() && $entity->canAnnotate(0, 'likes')) {
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
		$url = elgg_get_site_url() . "action/likes/delete?guid={$guid}";
		$params = array(
			'href' => $url,
			'text' => elgg_view_icon('thumbs-up-alt'),
			'title' => elgg_echo('likes:remove'),
			'is_action' => true,
			'is_trusted' => true,
		);
		$likes_button = elgg_view('output/url', $params);
	}

	echo $likes_button;
}
