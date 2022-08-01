<?php
/**
 * This view displays a single group tool
 *
 * @uses $vars['tool']   \Elgg\Groups\Tool
 * @uses $vars['entity'] \ElggGroup
 * @uses $vars['value']  (optional) tool value
 * @uses $vars['class']  (optional) field class
 */

$tool = elgg_extract('tool', $vars);
if (!$tool instanceof \Elgg\Groups\Tool) {
	return;
}

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => $tool->getLabel(),
	'#help' => $tool->getDescription(),
	'#class' => elgg_extract_class($vars),
	'name' => $tool->mapMetadataName(),
	'value' => 'yes',
	'default' => 'no',
	'switch' => true,
	'checked' => elgg_extract('value', $vars) === 'yes',
]);
