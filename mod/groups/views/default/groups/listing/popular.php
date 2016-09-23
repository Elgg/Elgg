<?php

/**
 * Renders a list of groups orderd by the membership count
 */
echo elgg_list_entities_from_relationship_count(array(
	'type' => 'group',
	'relationship' => 'member',
	'inverse_relationship' => false,
	'full_view' => false,
	'no_results' => elgg_echo('groups:none'),
));
