<?php
/**
 * Post comment river view
 */

$item = $vars['item'];
/* @var ElggRiverItem $item */

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

$keys = array(
	"river:comment:$type:$subtype",
	"river:comment:$type:default",
);
$summary = elgg_echo($keys, array($subject_link, $target_link));

echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],
	'message' => elgg_get_excerpt($comment->description),
	'summary' => $summary,
));
