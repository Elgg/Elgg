<?php
/**
 * Messageboard widget view
 */

use Elgg\Database\Clauses\OrderByClause;

$widget = elgg_extract('entity', $vars);
$owner = $widget->getOwnerEntity();

if (elgg_is_logged_in()) {
	echo elgg_view_form('messageboard/add', [
		'name' => 'elgg-messageboard',
	]);
}

$num_display = (int) $widget->num_display ?: 4;

$more_link = elgg_view_url(elgg_generate_url('collection:annotation:messageboard:owner', ['username' => $owner->username]), elgg_echo('link:view:all'));

echo elgg_list_annotations([
	'annotation_name' => 'messageboard',
	'guid' => $owner->guid,
	'limit' => $num_display,
	'pagination' => false,
	'order_by' => [
		new OrderByClause('n_table.time_created', 'DESC'),
		new OrderByClause('n_table.id', 'DESC'),
	],
	'widget_more' => $more_link,
]);

elgg_require_js('elgg/messageboard');
