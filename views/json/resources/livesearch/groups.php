<?php

elgg_gatekeeper();

$limit = get_input('limit', elgg_get_config('default_limit'));
$query = get_input('term', get_input('q'));
$input_name = get_input('name');

$options = [
	'query' => $query,
	'type' => 'group',
	'limit' => $limit,
	'sort' => 'name',
	'order' => 'ASC',
	'fields' => ['metadata' => ['name', 'username']],
	'item_view' => 'search/entity',
	'input_name' => $input_name,
];

$target_guid = get_input('match_target');
if ($target_guid) {
	$target = get_entity($target_guid);
} else {
	$target = elgg_get_logged_in_user_entity();
}

if (!$target || !$target->canEdit()) {
	forward('', '403');
}

if (get_input('match_owner', false)) {
	$options['owner_guid'] = (int) $target->guid;
}

if (get_input('match_membership', false)) {
	$dbprefix = elgg_get_config('dbprefix');
	$options['wheres'][] = "
		EXISTS(
			SELECT 1 FROM {$dbprefix}entity_relationships
			WHERE guid_one = $target->guid
			AND relationship = 'member'
			AND guid_two = e.guid
		)
	";
}

$body = elgg_list_entities($options, 'elgg_search');

echo elgg_view_page('', $body);
