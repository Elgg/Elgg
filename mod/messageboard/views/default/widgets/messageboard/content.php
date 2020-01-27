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

echo elgg_list_annotations([
	'annotation_name' => 'messageboard',
	'guid' => $owner->guid,
	'limit' => $num_display,
	'pagination' => false,
	'order_by' => [
		new OrderByClause('n_table.time_created', 'DESC'),
		new OrderByClause('n_table.id', 'DESC'),
	],
]);

$more_link = elgg_view('output/url', [
	'href' => elgg_generate_url('collection:annotation:messageboard:owner', [
		'username' => $owner->username,
	]),
	'text' => elgg_echo('link:view:all'),
	'is_trusted' => true,
]);

echo elgg_format_element('div', ['class' => 'elgg-widget-more'], $more_link);

elgg_require_js('elgg/messageboard');
