<?php

use Elgg\Database\QueryBuilder;

elgg_gatekeeper();

$limit = (int) elgg_extract('limit', $vars, elgg_get_config('default_limit'));
$query = elgg_extract('term', $vars, elgg_extract('q', $vars));
$input_name = elgg_extract('name', $vars);

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

$target_guid = (int) elgg_extract('match_target', $vars);
if ($target_guid) {
	$target = get_entity($target_guid);
} else {
	$target = elgg_get_logged_in_user_entity();
}

if (!$target || !$target->canEdit()) {
	forward('', '403');
}

if (elgg_extract('match_owner', $vars, false)) {
	$options['owner_guid'] = (int) $target->guid;
}

if (elgg_extract('match_membership', $vars, false)) {
	$options['wheres'][] = function (QueryBuilder $qb, $main_alias) use ($target) {
		$rel = $qb->subquery('entity_relationships');
		$rel->select('guid_two')
			->where($qb->compare('relationship', '=', 'member', ELGG_VALUE_STRING))
			->andWhere($qb->compare('guid_one', '=', $target->guid, ELGG_VALUE_GUID));
		
		return $qb->compare("{$main_alias}.guid", 'in', $rel->getSQL());
	};
}

$body = elgg_list_entities($options, 'elgg_search');

echo elgg_view_page('', $body);
