<?php

elgg_gatekeeper();

$limit = get_input('limit', elgg_get_config('default_limit'));
$query = get_input('term', get_input('q'));
$input_name = get_input('name');

elgg_set_http_header("Content-Type: application/json;charset=utf-8");

$options = [
	'query' => $query,
	'type' => 'user',
	'limit' => $limit,
	'sort' => 'name',
	'order' => 'ASC',
	'fields' => ['name', 'username'],
	'item_view' => 'search/entity',
	'input_name' => $input_name,
];

if (get_input('friends_only', false)) {
	$target_guid = get_input('match_target');
	if ($target_guid) {
		$target = get_entity($target_guid);
	} else {
		$target = elgg_get_logged_in_user_entity();
	}

	if (!$target || !$target->canEdit()) {
		forward('', '403');
	}
	
	$dbprefix = elgg_get_config('dbprefix');
	$options['wheres'][] = "
		EXISTS(
			SELECT 1 FROM {$dbprefix}entity_relationships
			WHERE guid_one = $target->guid
			AND relationship = 'friend'
			AND guid_two = e.guid
		)
	";
}

echo elgg_list_entities($options, 'elgg_search');
