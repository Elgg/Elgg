<?php

file_register_toggle();

echo elgg_list_entities([
	'type' => 'object',
	'subtype' => 'file',
	'no_results' => elgg_echo("file:none"),
	'distinct' => false,
]);
