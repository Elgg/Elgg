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
	'fields' => ['metadata' => ['name', 'username']],
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
	$options['wheres'][] = function(QueryBuilder $qb) use ($target) {
		$subquery = $qb->subquery('entity_relationships', 'er');
		$subquery->select('1')
			->where($qb->compare('er.guid_two', '=', 'e.guid'))
			->andWhere($qb->compare('er.relationship', '=', 'friend', ELGG_VALUE_STRING))
			->andWhere($qb->compare('er.guid_one', '=', $target->guid, ELGG_VALUE_INTEGER));

		return "EXISTS ({$subquery->getSQL()})";
	};
}

echo elgg_list_entities($options, 'elgg_search');
