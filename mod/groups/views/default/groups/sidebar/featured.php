<?php
/**
 * Featured groups
 *
 * @package ElggGroups
 */

elgg_push_context('widgets');

$featured_groups = elgg_list_entities_from_metadata(array(
	'metadata_name' => 'featured_group',
	'metadata_value' => 'yes',
	'type' => 'group',
	'full_view' => false,
	'pagination' => false,
		));

if ($featured_groups) {
	echo elgg_view_module('aside', elgg_echo("groups:featured"), $featured_groups);
}

elgg_pop_context();
