<?php
/**
 * Post comment river view
 */

$item = elgg_extract('item', $vars);
if (!$item instanceof ElggRiverItem) {
	return;
}

$comment = $item->getObjectEntity();
if (!$comment instanceof ElggComment) {
	return;
}

$subject = $item->getSubjectEntity();
$target = $item->getTargetEntity();

if (!$subject instanceof ElggEntity || !$target instanceof ElggEntity) {
	return;
}

$subject_link = elgg_view('output/url', [
	'href' => $subject->getURL(),
	'text' => $subject->getDisplayName(),
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
]);

$target_link = elgg_view('output/url', [
	'href' => $comment->getURL(),
	'text' => $target->getDisplayName(),
	'class' => 'elgg-river-target',
	'is_trusted' => true,
]);

$type = $target->getType();
$subtype = $target->getSubtype() ? $target->getSubtype() : 'default';

$key = false;
$keys = [
	"river:$type:$subtype:comment",
	"river:$type:default:comment",
];
foreach ($keys as $try_key) {
	if (elgg_language_key_exists($try_key)) {
		$key = $try_key;
		break;
	}
}

if ($key !== false) {
	$vars['summary'] = elgg_echo($key, [$subject_link, $target_link]);
}

$message = elgg_get_excerpt($comment->description);
if (elgg_substr($message, -3) === '...') {
	$message .= elgg_view('output/url', [
		'text' => elgg_echo('read_more'),
		'href' => $comment->getURL(),
		'is_trusted' => true,
		'class' => 'mls',
	]);
}

$vars['message'] = $message;

echo elgg_view('river/elements/layout', $vars);
