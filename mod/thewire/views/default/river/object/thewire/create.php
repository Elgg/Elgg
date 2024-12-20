<?php
/**
 * File river view.
 */

$item = elgg_extract('item', $vars);
if (!$item instanceof \ElggRiverItem) {
	return;
}

$vars['message'] = elgg_format_html((string) $item->getObjectEntity()?->description, [
	'sanitize' => false,
	'autop' => false,
	'parse_thewire_hashtags' => true,
]);

$subject = $item->getSubjectEntity();
$subject_link = elgg_view_entity_url($subject, ['class' => 'elgg-river-subject']);

$object_link = elgg_view('output/url', [
	'href' => elgg_generate_url('collection:object:thewire:owner', [
		'username' => $subject->username,
	]),
	'text' => elgg_echo('thewire:wire'),
	'class' => 'elgg-river-object',
	'is_trusted' => true,
]);

$vars['summary'] = elgg_echo('river:object:thewire:create', [$subject_link, $object_link]);

echo elgg_view('river/elements/layout', $vars);
