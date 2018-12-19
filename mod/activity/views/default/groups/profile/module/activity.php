<?php
/**
 * Groups latest activity
 */

use Elgg\Activity\GroupRiverFilter;
use Elgg\Database\QueryBuilder;

$group = elgg_extract('entity', $vars);
if (!$group instanceof \ElggGroup) {
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

$content = elgg_list_river([
	'limit' => 4,
	'pagination' => false,
	'wheres' => [
		function (QueryBuilder $qb, $main_alias) use ($group) {
			$group = new GroupRiverFilter($group);
			
			return $group($qb, $main_alias);
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
