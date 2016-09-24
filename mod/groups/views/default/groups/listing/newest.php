<?php

/**
 * Renders a list of groups by creation date
 */
echo elgg_list_entities(array(
	'type' => 'group',
	'full_view' => false,
	'no_results' => elgg_echo('groups:none'),
	'distinct' => false,
));
