<?php

/**
 * User view in Friends Picker
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['entity'] User entity
 * @uses $vars['input_name'] Name of the returned data array
 * @uses $vars['input_values'] An array of preselected values in the array
 */
/* @var ElggEntity $entity */
$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	return;
}

$input_name = elgg_extract('input_name', $vars, 'friend');
$input_values = (array) elgg_extract('input_values', $vars, array());

echo '<label>';
echo elgg_view('input/checkbox', array(
	'name' => "{$input_name}[]",
	'value' => $entity->guid,
	'default' => false,
	'class' => 'elgg-friendspicker-checkbox',
	'checked' => in_array($entity->guid, $input_values),
));

echo elgg_view('output/img', array(
	'src' => $entity->getIconURL('tiny'),
	'class' => 'elgg-friendspicker-icon',
));
echo elgg_format_element('span', ['class' => 'elgg-friendspicker-name'], $entity->getDisplayName());
echo '</label>';
