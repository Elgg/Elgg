<?php

$options = [
	'distinct' => false,
	'no_results' => elgg_echo('river:none'),
];

$type = elgg_extract('river_type', $vars, 'all');
$subtype = elgg_extract('river_subtype', $vars, 'all');

if ($type != 'all') {
	$options['type'] = $type;
	if ($subtype) {
		$options['subtype'] = $subtype;
	}
}

echo elgg_list_river($options);