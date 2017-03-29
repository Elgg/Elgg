<?php

$lower = elgg_extract('lower', $vars);
$upper = elgg_extract('upper', $vars);

$owner = elgg_extract('target', $vars);

if ($lower) {
	$lower = (int) $lower;
}

if ($upper) {
	$upper = (int) $upper;
}

$options = [
	'type' => 'object',
	'subtype' => 'blog',
	'full_view' => false,
	'no_results' => elgg_echo('blog:none'),
	'preload_owners' => true,
	'distinct' => false,
];

if ($owner instanceof ElggGroup) {
	$options['container_guid'] = $owner->guid;
} elseif ($owner instanceof ElggUser) {
	$options['owner_guid'] = $owner->guid;
}

if ($lower) {
	$options['created_time_lower'] = $lower;
}

if ($upper) {
	$options['created_time_upper'] = $upper;
}

echo elgg_list_entities($options);
