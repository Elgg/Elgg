<?php
/**
 * Featured groups
 *
 * @package ElggGroups
 */

elgg_push_context('widgets');

$content = elgg_list_entities_from_metadata([
	'metadata_name' => 'featured_group',
	'metadata_value' => 'yes',
	'type' => 'group',
	'pagination' => false,
]);

elgg_pop_context();

if (empty($content)) {
	return;
}

echo elgg_view_module('aside', elgg_echo('groups:featured'), $content);
