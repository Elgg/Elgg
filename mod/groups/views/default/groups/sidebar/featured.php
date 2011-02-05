<?php
/**
 * Featured groups
 *
 * @package ElggGroups
 */

$featured_groups = elgg_get_entities_from_metadata(array(
	'metadata_name' => 'featured_group',
	'metadata_value' => 'yes',
	'types' => 'group',
	'limit' => 10,
));

if ($featured_groups) {

	elgg_push_context('widgets');
	$body = '';
	foreach ($featured_groups as $group) {
		$body .= elgg_view_entity($group, false);
	}
	elgg_pop_context();

	echo elgg_view('layout/objects/module', array(
		'title' => elgg_echo("groups:featured"),
		'body' => $body,
		'class' => 'elgg-module-aside',
	));
}
