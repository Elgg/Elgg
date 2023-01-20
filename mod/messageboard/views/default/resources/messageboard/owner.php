<?php
/**
 * Elgg Message board index page
 */

use Elgg\Database\Clauses\OrderByClause;

elgg_require_js('elgg/messageboard');

$page_owner = elgg_get_page_owner_entity();

$history_username = (string) elgg_extract('history_username', $vars);
$history_user = elgg_get_user_by_username($history_username);

elgg_push_breadcrumb($page_owner->getDisplayName(), $page_owner->getURL());

$options = [
	'annotation_name' => 'messageboard',
	'guid' => $page_owner->guid,
	'order_by' => [
		new OrderByClause('n_table.time_created', 'DESC'),
		new OrderByClause('n_table.id', 'DESC'),
	],
	'no_results' => elgg_echo('messageboard:none'),
];

$title = elgg_echo('messageboard:owner', [$page_owner->getDisplayName()]);

if ($history_user instanceof \ElggUser) {
	$options['annotations_owner_guid'] = $history_user->guid;
	$title = elgg_echo('messageboard:owner_history', [$history_user->getDisplayName(), $page_owner->getDisplayName()]);

	elgg_push_breadcrumb(elgg_echo('messageboard:board'), elgg_generate_url('collection:annotation:messageboard:owner', ['username' => $page_owner->username]));
}

echo elgg_view_page($title, [
	'content' => elgg_list_annotations($options),
	'filter' => false,
]);
