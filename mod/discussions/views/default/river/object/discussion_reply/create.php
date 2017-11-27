<?php
/**
 * River view for discussion replies
 */

$item = elgg_extract('item', $vars);
if (!$item instanceof ElggRiverItem) {
	return;
}

$reply = $item->getObjectEntity();
if (!$reply instanceof ElggDiscussionReply) {
	return;
}

$subject = $item->getSubjectEntity();
$target = $item->getTargetEntity();
if (!$subject instanceof ElggEntity || !$target instanceof ElggEntity) {
	return;
}

$subject_link = elgg_view('output/url', [
	'href' => $subject->getURL(),
	'text' => $subject->name,
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
]);

$target_link = elgg_view('output/url', [
	'href' => $reply->getURL(),
	'text' => $target->getDisplayName(),
	'class' => 'elgg-river-target',
	'is_trusted' => true,
]);

$vars['summary'] = elgg_echo('river:object:discussion_reply:reply', [$subject_link, $target_link]);

$vars['message'] = elgg_get_excerpt($reply->description);

echo elgg_view('river/elements/layout', $vars);
