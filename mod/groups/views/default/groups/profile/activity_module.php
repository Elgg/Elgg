<?php
/**
 * Groups latest activity
 *
 * @todo add people joining group to activity
 *
 * @package Groups
 */

$group = elgg_extract('entity', $vars);
if (!($group instanceof \ElggGroup)) {
	return;
}

if ($group->activity_enable == 'no') {
	return;
}

$all_link = elgg_view('output/url', [
	'href' => "groups/activity/$group->guid",
	'text' => elgg_echo('link:view:all'),
	'is_trusted' => true,
]);

elgg_push_context('widgets');
$db_prefix = elgg_get_config('dbprefix');
$content = elgg_list_river([
	'limit' => 4,
	'pagination' => false,
	'joins' => [
		"JOIN {$db_prefix}entities e1 ON e1.guid = rv.object_guid",
		"LEFT JOIN {$db_prefix}entities e2 ON e2.guid = rv.target_guid",
	],
	'wheres' => [
		"(e1.container_guid = $group->guid OR e2.container_guid = $group->guid)",
	],
	'no_results' => elgg_echo('groups:activity:none'),
]);
elgg_pop_context();

echo elgg_view('groups/profile/module', [
	'title' => elgg_echo('groups:activity'),
	'content' => $content,
	'all_link' => $all_link,
]);
