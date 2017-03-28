<?php

$subject = elgg_extract('target', $vars);

$options = [
	'distinct' => false,
	'no_results' => elgg_echo('river:none'),
	'subject_guid' => (int) $subject->guid,
];

$type = elgg_extract('river_type', $vars, 'all');
$subtype = elgg_extract('river_subtype', $vars, 'all');

if ($type != 'all') {
	$options['type'] = $type;
	if ($subtype != 'all') {
		$options['subtype'] = $subtype;
	}
}

echo elgg_list_river($options);