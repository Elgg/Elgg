<?php
/**
 * Groups latest activity
 *
 * @todo add people joining group to activity
 */

use Elgg\Database\QueryBuilder;
use Elgg\Database\Clauses\JoinClause;

$group = elgg_extract('entity', $vars);
if (!$group instanceof \ElggGroup) {
	return;
}

if (!$group->isToolEnabled('activity')) {
	return;
}

$all_link = elgg_view('output/url', [
	'href' => elgg_generate_url('collection:river:group', [
		'guid' => $group->guid,
	]),
	'text' => elgg_echo('link:view:all'),
	'is_trusted' => true,
]);

elgg_push_context('widgets');

$on_object = function (QueryBuilder $qb, $joined_alias, $main_alias) {
	return $qb->compare("{$joined_alias}.guid", '=', "{$main_alias}.object_guid");
};
$on_target = function (QueryBuilder $qb, $joined_alias, $main_alias) {
	return $qb->compare("{$joined_alias}.guid", '=', "{$main_alias}.target_guid");
};

$content = elgg_list_river([
	'limit' => 4,
	'pagination' => false,
	'joins' => [
		new JoinClause('entities', 'e1', $on_object),
		new JoinClause('entities', 'e2', $on_target, 'left'),
	],
	'wheres' => [
		function (QueryBuilder $qb, $main_alias) use ($group) {
			$wheres = [];
			$wheres[] = $qb->compare("{$main_alias}.object_guid", '=', $group->guid);
			$wheres[] = $qb->compare('e1.container_guid', '=', $group->guid);
			$wheres[] = $qb->compare('e2.container_guid', '=', $group->guid);
			
			return $qb->merge($wheres, 'OR');
		},
	],
	'no_results' => elgg_echo('river:none'),
]);
elgg_pop_context();

echo elgg_view('groups/profile/module', [
	'title' => elgg_echo('collection:river:group'),
	'content' => $content,
	'all_link' => $all_link,
]);
