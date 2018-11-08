<?php

$owner = elgg_extract('entity', $vars);

// List files
$options = [
	'type' => 'object',
	'subtype' => 'file',
	'no_results' => elgg_echo("file:none"),
	'distinct' => false,
];

if ($owner instanceof ElggGroup) {
	$options['container_guid'] = $owner->guid;
} else {
	$options['owner_guid'] = $owner->guid;
}

file_register_toggle();

echo elgg_list_entities($options);
