<?php

elgg_gatekeeper();

$limit = (int) elgg_extract('limit', $vars, elgg_get_config('default_limit'));
$query = elgg_extract('term', $vars, elgg_extract('q', $vars));
$input_name = elgg_extract('name', $vars);

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

if (elgg_extract('friends_only', $vars, false)) {
	$target_guid = (int) elgg_extract('match_target', $vars);
	if ($target_guid) {
		$target = get_entity($target_guid);
	} else {
		$target = elgg_get_logged_in_user_entity();
	}
	
	if (!$target || !$target->canEdit()) {
		throw new \Elgg\EntityPermissionsException();
	}
	
	$options['wheres'][] = function(\Elgg\Database\QueryBuilder $qb, $main_alias) use ($target) {
		$subquery = $qb->subquery('entity_relationships', 'er');
		$subquery->select('er.guid_two')
			->andWhere($qb->compare('er.relationship', '=', 'friend', ELGG_VALUE_STRING))
			->andWhere($qb->compare('er.guid_one', '=', $target->guid, ELGG_VALUE_GUID));

		return $qb->compare("{$main_alias}.guid", 'in', $subquery->getSQL());
	};
}

// by default search in all users,
// with 'include_banned' => false, only search in 'allowed' users
if (!(bool) elgg_extract('include_banned', $vars, true)) {
	$options['metadata_name_value_pairs'][] = [
		'name' => 'banned',
		'value' => 'no',
	];
}

$body = elgg_list_entities($options, 'elgg_search');

echo elgg_view_page('', $body);
