<?php
/**
 * Short summary of the action that occurred
 *
 * @vars['item'] ElggRiverItem
 */

$item = elgg_extract('item', $vars);
if (!$item instanceof ElggRiverItem) {
	return;
}

$subject = $item->getSubjectEntity();
if (!$subject instanceof ElggEntity) {
	return;
}

$object = $item->getObjectEntity();
if (!$object instanceof ElggEntity) {
	return;
}

$subject_link = elgg_view_entity_url($subject, ['class' => 'elgg-river-subject']);

$object_link = elgg_view('output/url', [
	'href' => $object->getURL(),
	'text' => elgg_get_excerpt($object->getDisplayName(), 100),
	'class' => 'elgg-river-object',
	'is_trusted' => true,
]);

$action = $item->action_type;
$type = $item->type;
$subtype = $item->subtype;

// if activity happened in a group
$group_string = '';
$container = $object->getContainerEntity();
if ($container instanceof ElggGroup && $container->guid != elgg_get_page_owner_guid()) {
	$group_string = elgg_echo('river:ingroup', [elgg_view_entity_url($container)]);
}

// check summary translation keys
$key = false;
$keys = [
	"river:{$type}:{$subtype}:{$action}",
	"river:{$type}:{$subtype}:default",
	"river:{$type}:{$action}",
	"river:{$type}:default",
];
foreach ($keys as $try_key) {
	if (elgg_language_key_exists($try_key)) {
		$key = $try_key;
		break;
	}
}

$summary = '';
if ($key !== false) {
	$summary = elgg_echo($key, [$subject_link, $object_link]);
}

echo trim("$summary $group_string");
