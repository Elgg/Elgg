<?php
/**
 * Forum reply entity view
*/

$reply = elgg_extract('entity', $vars);
if (!($reply instanceof \ElggDiscussionReply)) {
	return;
}

if (elgg_in_context('activity')) {
	$content = '<div class="elgg-output elgg-inner" data-role="comment-text">';
	$content .= elgg_view('output/text', [
		'value' => elgg_get_excerpt($reply->description)
	]);
	$content .= '</div>';
} else {
	$content = elgg_view('output/longtext', [
		'value' => $reply->description,
		'class' => 'elgg-inner',
		'data-role' => 'discussion-reply-text',
	]);
}

$params = [
	'entity' => $reply,
	'content' => $content,
	'handler' => 'discussion_reply',
	'icon' => elgg_view_entity_icon($reply->getOwnerEntity(), 'tiny'),
];
$params = $params + $vars;
echo elgg_view('object/elements/summary', $params);
