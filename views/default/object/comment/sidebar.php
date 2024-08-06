<?php
/**
 * Show a comment in the sidebar
 *
 * @uses $vars['entity'] the comment to show
 */

$comment = elgg_extract('entity', $vars);
if (!$comment instanceof \ElggComment) {
	return;
}

$entity = $comment->getContainerEntity();
$commenter = $comment->getOwnerEntity();
if (!$entity instanceof \ElggEntity || !$commenter instanceof \ElggEntity) {
	return;
}

$friendlytime = elgg_view_friendly_time($comment->time_created);
$excerpt = elgg_get_excerpt((string) $comment->description, 80);
$posted = elgg_echo('generic_comment:on', [elgg_view_entity_url($commenter), elgg_view_entity_url($entity)]);

$body = elgg_format_element('span', ['class' => 'elgg-subtext'], "{$posted} ({$friendlytime}): {$excerpt}");

echo elgg_view_image_block(elgg_view_entity_icon($commenter, 'small'), $body);
