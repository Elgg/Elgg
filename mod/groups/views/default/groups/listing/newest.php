<?php

/**
 * Renders a list of most recent groups
 */
echo elgg_list_entities(array(
	'type' => 'group',
	'full_view' => false,
	'no_results' => elgg_echo('groups:none'),
	'distinct' => false,
));
