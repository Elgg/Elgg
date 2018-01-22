<?php
/**
 * History of this page
 *
 * @uses $vars['page']
 */

$title = elgg_echo('pages:history');
$content = '';

$page = elgg_extract('page', $vars);
if ($page instanceof ElggPage) {
	elgg_push_context('widgets');
	
	$content = elgg_list_annotations([
		'guid' => $page->guid,
		'annotation_name' => 'page',
		'limit' => max(20, elgg_get_config('default_limit')),
		'order_by' => [
			new \Elgg\Database\Clauses\OrderByClause('n_table.time_created', 'desc'),
			new \Elgg\Database\Clauses\OrderByClause('n_table.id', 'desc'),
		],
	]);
	
	elgg_pop_context();
}

echo elgg_view_module('aside', $title, $content);
