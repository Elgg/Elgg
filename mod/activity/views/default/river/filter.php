<?php
/**
 * Content filter for river
 *
 * @uses $vars[]
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
		$options["type=$type"] = elgg_echo('river:select', [elgg_echo("collection:$type")]);
		continue;
	}
	
	foreach ($subtypes as $subtype) {
		$options["type=$type&subtype=$subtype"] = elgg_echo('river:select', [elgg_echo("collection:$type:$subtype")]);
	}
}

$filter = elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('filter'),
	'#class' => 'elgg-river-selector',
	'id' => 'elgg-river-selector',
	'options_values' => $options,
	'value' => elgg_extract('selector', $vars),
]);

echo elgg_format_element('div', ['class' => 'clearfix'], $filter);
