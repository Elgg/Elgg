<?php
/**
 * Short summary of the comment action
 *
 * TODO Provide own link also for the comment as soon as
 * the comment pagination supports this.
 *
 * @vars['item'] ElggRiverItem
 */

$item = $vars['item'];

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

$action = $item->action_type;
$type = $target->getType();
$subtype = $target->getSubtype() ? $target->getSubtype() : 'default';

// Check summary translation keys.
// Uses the $type:$subtype if that's defined, otherwise just uses $type:default
$key = "river:$action:$type:$subtype";
$summary = elgg_echo($key, array($subject_link, $target_link));

if ($summary == $key) {
	$key = "river:$action:$type:default";
	$summary = elgg_echo($key, array($subject_link, $object_link));
}

echo $summary;
