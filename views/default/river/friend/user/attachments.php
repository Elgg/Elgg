<?php

/**
 * Relationship attachment view
 *
 * @uses $vars['result'] River result object
 */
$result = elgg_extract('result', $vars);

if (!$result instanceof ElggRelationship) {
	return;
}

$subject = get_entity($result->guid_one);
$object = get_entity($result->guid_two);

if (!$subject || !$object) {
	return;
}

$subject_icon = elgg_view_entity_icon($subject, 'small');
$object_icon = elgg_view_entity_icon($object, 'small');

echo $subject_icon . elgg_view_icon('arrow-right', ['class' => 'mll mrl']) . $object_icon;