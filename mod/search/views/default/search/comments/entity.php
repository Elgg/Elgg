<?php
/**
 * Default search view for a comment
 *
 * @uses $vars['entity']
 */

$entity = $vars['entity'];
$comments_data = $entity->getVolatileData('search_comments_data');
$comment_data = array_shift($comments_data);
$entity->setVolatileData('search_comments_data', $comments_data);

$owner = get_entity($comment_data['owner_guid']);

if ($owner instanceof ElggUser) {
	$icon = elgg_view_entity_icon($owner, 'tiny');
} else {
	$icon = '';
}

// @todo Sometimes we find comments on entities we can't display...
if ($entity->getVolatileData('search_unavailable_entity')) {
	$title = elgg_echo('search:comment_on', array(elgg_echo('search:unavailable_entity')));
	// keep anchor for formatting.
	$title = "<a>$title</a>";
} else {
	if ($entity->getType() == 'object') {
		$title = $entity->title;
	} else {
		$title = $entity->name;
	}

	if (!$title) {
		$title = elgg_echo('item:' . $entity->getType() . ':' . $entity->getSubtype());
	}

	if (!$title) {
		$title = elgg_echo('item:' . $entity->getType());
	}

	$title = elgg_echo('search:comment_on', array($title));

	// @todo this should use something like $comment->getURL()
	$url = $entity->getURL() . '#comment_' . $comment_data['annotation_id'];
	$title = "<a href=\"$url\">$title</a>";
}

$description = $comment_data['text'];
$tc = $comment_data['time_created'];
$time = elgg_view_friendly_time($tc);

$body = "<p class=\"mbn\">$title</p>$description";
$body .= "<p class=\"elgg-subtext\">$time</p>";

echo elgg_view_image_block($icon, $body);
