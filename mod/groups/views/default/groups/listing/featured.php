<?php

/**
 * Renders a list of featured groups
 */
echo elgg_list_entities_from_metadata(array(
	'type' => 'group',
	'metadata_name' => 'featured_group',
	'metadata_value' => 'yes',
	'full_view' => false,
	'no_results' => elgg_echo('groups:nofeatured'),
));
