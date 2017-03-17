<?php
/**
 * Forum reply entity view
*/

$reply = elgg_extract('entity', $vars, false);
/* @var ElggDiscussionReply $reply */

if (!$reply) {
	return true;
}

$metadata = elgg_view_menu('entity', array(
	'entity' => $vars['entity'],
	'handler' => 'discussion_reply',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
		));

if (elgg_in_context('activity')) {
	$content = '<div class="elgg-output elgg-inner" data-role="comment-text">';
	$content .= elgg_view('output/text', array(
		'value' => elgg_get_excerpt($reply->description)
	));
	$content .= '</div>';
} else {
	$content = elgg_view('output/longtext', array(
		'value' => $reply->description,
		'class' => 'elgg-inner',
		'data-role' => 'discussion-reply-text',
	));
}

$subtitle = elgg_view('page/elements/by_line', ['entity' => $reply]);

$params = array(
	'entity' => $reply,
	'metadata' => $metadata,
	'subtitle' => $subtitle,
	'content' => $content,
	'icon' => elgg_view_entity_icon($reply->getOwnerEntity(), 'tiny'),
);
$params = $params + $vars;
echo elgg_view('object/elements/summary', $params);
