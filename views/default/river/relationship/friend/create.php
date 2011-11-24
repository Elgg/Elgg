<?php
/**
 * Create friend river view
 */
$subject = $vars['item']->getSubjectEntity();
$object = $vars['item']->getObjectEntity();

$subject_icon = elgg_view_entity_icon($subject, 'tiny');
$object_icon = elgg_view_entity_icon($object, 'tiny');

echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],
	'attachments' => $subject_icon . elgg_view_icon('arrow-right') . $object_icon,
));
