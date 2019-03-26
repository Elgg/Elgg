<?php

/**
 * Renders a list of groups by creation date
 */
echo elgg_list_entities([
	'type' => 'group',
	'full_view' => false,
	'no_results' => elgg_echo('groups:none'),
]);
