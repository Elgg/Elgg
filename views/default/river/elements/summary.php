<?php
/**
 * Short summary of the action that occurred
 *
 * @vars['item'] ElggRiverItem
 */

$item = $vars['item'];

$subject = $item->getSubjectEntity();
$object = $item->getObjectEntity();

$subject_link = elgg_view('output/url', [
	'href' => $subject->getURL(),
	'text' => $subject->name,
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
]);

$object_text = $object->title ? $object->title : $object->name;
$object_link = elgg_view('output/url', [
	'href' => $object->getURL(),
	'text' => elgg_get_excerpt($object_text, 100),
	'class' => 'elgg-river-object',
	'is_trusted' => true,
]);

$action = $item->action_type;
$type = $item->type;
$subtype = $item->subtype ? $item->subtype : 'default';

// check summary translation keys.
// will use the $type:$subtype if that's defined, otherwise just uses $type:default
$key = "river:$action:$type:$subtype";
$summary = elgg_echo($key, [$subject_link, $object_link]);

if ($summary == $key) {
	$key = "river:$action:$type:default";
	$summary = elgg_echo($key, [$subject_link, $object_link]);
}

echo $summary;
