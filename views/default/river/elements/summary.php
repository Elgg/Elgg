<?php
/**
 * Short summary of the action that occurred
 *
 * @vars['item'] ElggRiverItem
 */

$item = $vars['item'];
/* @var ElggRiverItem $item */

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

// if activity happened in a group
$group_string = '';
$container = $object->getContainerEntity();
if ($container instanceof ElggGroup && $container->guid != elgg_get_page_owner_guid()) {
	$group_link = elgg_view('output/url', [
		'href' => $container->getURL(),
		'text' => $container->name,
		'is_trusted' => true,
	]);
	$group_string = elgg_echo('river:ingroup', [$group_link]);
}

// check summary translation keys.
// will use the $type:$subtype if that's defined, otherwise just uses $type:default
$key = "river:$action:$type:$subtype";
if (!elgg_language_key_exists($key)) {
	$key = "river:$action:$type:default";
}
$summary = elgg_echo($key, array($subject_link, $object_link));

echo trim("$summary $group_string");
