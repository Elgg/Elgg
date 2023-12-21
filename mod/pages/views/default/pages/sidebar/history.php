<?php
/**
 * History of this page
 *
 * @uses $vars['page']
 */

use Elgg\Database\Clauses\OrderByClause;

$content = '';

$page = elgg_extract('page', $vars, elgg_extract('entity', $vars));
if ($page instanceof \ElggPage) {
	elgg_push_context('widgets');
	
	$content = elgg_list_annotations([
		'guid' => $page->guid,
		'annotation_name' => 'page',
		'limit' => max(20, elgg_get_config('default_limit')),
		'order_by' => [
			new OrderByClause('a_table.time_created', 'desc'),
			new OrderByClause('a_table.id', 'desc'),
		],
	]);
	
	elgg_pop_context();
}

if (empty($content)) {
	return;
}

echo elgg_view_module('aside', elgg_echo('pages:history'), $content);
