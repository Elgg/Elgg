<?php
/**
 * Content filter for river
 *
 * @uses $vars[]
 */

$events = elgg_get_registered_river_events();
if (empty($events)) {
	return;
}

elgg_require_js('river/filter');

// create selection array
$options = [
	'type=all' => elgg_echo('river:select', [elgg_echo('all')]),
];

foreach ($events as $type => $subtypes) {
	foreach ($subtypes as $subtype => $actions) {
		if ($subtype == 'all') {
			$options["type=$type"] = elgg_echo('river:select', [elgg_echo("collection:$type")]);
			continue;
		}

		if ($type == 'object' && $subtype == 'comment') {
			$options["type=all&action=comment"] = elgg_echo('river:select', [elgg_echo("collection:$type:$subtype")]);
		} else {
			$options["type=$type&subtype=$subtype"] = elgg_echo('river:select', [elgg_echo("collection:$type:$subtype")]);
		}
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
