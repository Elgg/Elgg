<?php
/**
 * Show all site river activity
 */

use Elgg\Database\Clauses\OrderByClause;

$content = elgg_view('river/listing/all', [
	'entity_type' => preg_replace('[\W]', '', get_input('type', 'all')),
	'entity_subtype' => preg_replace('[\W]', '', get_input('subtype', '')),
	'show_filter' => true,
	'show_comments' => false,
	'options' => [
		'order_by' => new OrderByClause('last_action', 'DESC'),
	],
]);

echo elgg_view_page(elgg_echo('river:all'), [
	'content' => $content,
	'sidebar' => elgg_view('river/sidebar'),
	'filter_value' => 'all',
	'class' => 'elgg-river-layout',
	
	// set type/subtype to trick filter menu event handler to consistently generate tabs (needed because of index resource)
	'entity_type' => 'river',
	'entity_subtype' => 'river',
]);
