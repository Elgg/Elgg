<?php
/**
 * Content filter for river
 *
 * @uses $vars['selector']       the current selector (for BC reasons)
 * @uses $vars['entity_type']    the current entity type selector
 * @uses $vars['entity_subtype'] the current entity subtype selector
 */

$registered_entities = elgg_extract('registered_entity_types', $vars, get_registered_entity_types());
if (empty($registered_entities)) {
	return;
}

elgg_require_js('river/filter');

// create selection array
$options = [
	'type=all' => elgg_echo('river:select', [elgg_echo('all')]),
];

foreach ($registered_entities as $type => $subtypes) {
	// subtype will always be an array.
	if (empty($subtypes)) {
		$options["type={$type}"] = elgg_echo('river:select', [elgg_echo("collection:{$type}")]);
		continue;
	}
	
	foreach ($subtypes as $subtype) {
		$options["type={$type}&subtype={$subtype}"] = elgg_echo('river:select', [elgg_echo("collection:{$type}:{$subtype}")]);
	}
}

$value = elgg_extract('selector', $vars);
if (empty($value)) {
	$entity_type = elgg_extract('entity_type', $vars, 'all');
	$entity_subtype = elgg_extract('entity_subtype', $vars);
	
	$value = "type={$entity_type}";
	if (!empty($entity_subtype)) {
		$value .= "&subtype={$entity_subtype}";
	}
}

$filter = elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('filter'),
	'#class' => 'elgg-river-selector',
	'id' => 'elgg-river-selector',
	'options_values' => $options,
	'value' => $value,
]);

echo elgg_format_element('div', ['class' => 'clearfix'], $filter);
