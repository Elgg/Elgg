<?php
/**
 * Update avatar river view
 */

$item = elgg_extract('item', $vars);
if (!$item instanceof ElggRiverItem) {
	return;
}

$subject = $item->getSubjectEntity();
if (!$subject instanceof ElggUser) {
	return;
}

$subject_link = elgg_view('output/url', [
	'href' => $subject->getURL(),
	'text' => $subject->getDisplayName(),
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
]);

$vars['summary'] = elgg_echo('river:update:user:avatar', [$subject_link]);
$vars['attachments'] = elgg_view_entity_icon($subject, 'tiny', [
	'use_hover' => false,
	'use_link' => false,
]);
$vars['responses'] = false;

echo elgg_view('river/elements/layout', $vars);
