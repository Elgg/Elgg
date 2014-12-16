<?php
/**
 * Create friend river view
 */
$item = $vars['item'];
/* @var ElggRiverItem $item */

$subject = $item->getSubjectEntity();
$object = $item->getObjectEntity();

$subject_icon = elgg_view_entity_icon($subject, 'tiny');
$object_icon = elgg_view_entity_icon($object, 'tiny');

echo elgg_view('river/elements/layout', array(
	'item' => $item,
	'attachments' => $subject_icon . elgg_view_icon('arrow-right') . $object_icon,
));
