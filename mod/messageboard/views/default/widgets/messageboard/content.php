<?php
/**
 * Messageboard widget view
 */

use Elgg\Database\Clauses\OrderByClause;

$widget = elgg_extract('entity', $vars);
if (!$widget instanceof \ElggWidget) {
	return;
}

if (elgg_is_logged_in()) {
	echo elgg_view_form('messageboard/add', [
		'name' => 'elgg-messageboard',
	]);
}

$num_display = (int) $widget->num_display ?: 4;

echo elgg_list_annotations([
	'annotation_name' => 'messageboard',
	'guid' => $widget->owner_guid,
	'limit' => $num_display,
	'pagination' => false,
	'order_by' => [
		new OrderByClause('a_table.time_created', 'DESC'),
		new OrderByClause('a_table.id', 'DESC'),
	],
	'widget_more' => elgg_view_url($widget->getURL(), elgg_echo('link:view:all')),
]);

elgg_import_esm('elgg/messageboard');
