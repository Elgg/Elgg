<?php

if (!elgg_is_active_plugin('friends')) {
	return;
}

$subject = elgg_extract('target', $vars);

$options = [
	'distinct' => false,
	'no_results' => elgg_echo('river:none'),
	'relationship_guid' => (int) $subject->guid,
	'relationship' => 'friend',
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
