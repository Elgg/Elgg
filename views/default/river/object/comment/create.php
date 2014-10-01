<?php
/**
 * Post comment river view
 */

$item = $vars['item'];

$comment = $item->getObjectEntity();
$subject = $item->getSubjectEntity();
$target = $item->getTargetEntity();

$subject_link = elgg_view('output/url', array(
	'href' => $subject->getURL(),
	'text' => $subject->name,
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
));

$target_link = elgg_view('output/url', array(
	'href' => $target->getURL(),
	'text' => $target->getDisplayName(),
	'class' => 'elgg-river-target',
	'is_trusted' => true,
));

$type = $target->getType();
$subtype = $target->getSubtype() ? $target->getSubtype() : 'default';
$key = "river:comment:$type:$subtype";
$summary = elgg_echo($key, array($subject_link, $target_link), '', true);
if (false === $summary) {
	$key = "river:comment:$type:default";
	$summary = (string)elgg_echo($key, array($subject_link, $target_link), '', true);
}

echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],
	'message' => elgg_get_excerpt($comment->description),
	'summary' => $summary,
));
