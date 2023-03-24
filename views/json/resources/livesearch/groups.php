<?php

use Elgg\Exceptions\Http\EntityPermissionsException;

elgg_gatekeeper();

$options = [
	'query' => elgg_extract('term', $vars),
	'type' => 'group',
	'limit' => elgg_extract('limit', $vars),
	'sort_by' => [
		'property_type' => 'metadata',
		'property' => 'name',
		'direction' => 'ASC',
	],
	'fields' => ['metadata' => ['name']],
	'item_view' => elgg_extract('item_view', $vars, 'search/entity'),
	'input_name' => elgg_extract('name', $vars),
];

$target_guid = (int) elgg_extract('match_target', $vars);
if ($target_guid) {
	$target = get_entity($target_guid);
} else {
	$target = elgg_get_logged_in_user_entity();
}

if (!$target instanceof \ElggEntity || !$target->canEdit()) {
	throw new EntityPermissionsException();
}

if (elgg_extract('match_owner', $vars, false)) {
	$options['owner_guid'] = (int) $target->guid;
}

if (elgg_extract('match_membership', $vars, false)) {
	$options['relationship'] = 'member';
	$options['relationship_guid'] = $target->guid;
}

$body = elgg_list_entities($options, 'elgg_search');

echo elgg_view_page('', $body);
