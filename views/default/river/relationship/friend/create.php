<?php
/**
 * Create friend river view
 */

$item = elgg_extract('item', $vars);
if (!$item instanceof ElggRiverItem) {
	return;
}

$subject = $item->getSubjectEntity();
$object = $item->getObjectEntity();

if (!$subject instanceof ElggUser || !$object instanceof ElggUser) {
	return;
}

$subject_icon = elgg_view_entity_icon($subject, 'small');
$object_icon = elgg_view_entity_icon($object, 'small');

$vars['attachments'] = $subject_icon . elgg_view_icon('arrow-right') . $object_icon;
$vars['responses'] = false;

echo elgg_view('river/elements/layout', $vars);
