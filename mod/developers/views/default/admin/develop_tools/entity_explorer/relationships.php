<?php
/**
 * List all the relationships of the given entity
 *
 * @uses $vars['entity'] the entity to inspect
 */

use Elgg\Database\Clauses\OrderByClause;
use Elgg\Database\QueryBuilder;

/* @var $entity \ElggEntity */
$entity = elgg_extract('entity', $vars);

$entity_relationships = elgg_get_relationships([
	'limit' => false,
	'relationship_guid' => $entity->guid,
	'order_by' => new OrderByClause(function(QueryBuilder $qb, $main_alias) {
		return "{$main_alias}.id";
	}, 'asc'),
]);

if (empty($entity_relationships)) {
	$relationship_info = elgg_echo('notfound');
} else {
	$relationship_columns = ['id', 'time_created', 'guid_one', 'relationship', 'guid_two'];

	$relationship_info = '<table class="elgg-table">';
	$relationship_info .= '<thead><tr>';
	foreach ($relationship_columns as $relationship_col) {
		$relationship_info .= elgg_format_element('th', [], $relationship_col);
	}
	
	$relationship_info .= '<th>&nbsp;</th>';
	$relationship_info .= '</tr></thead>';
	$relationship_info .= '<tbody>';
	
	foreach ($entity_relationships as $relationship) {
		$relationship_info .= '<tr>';
		foreach ($relationship_columns as $relationship_col) {
			$relationship_info .= elgg_format_element('td', [], $relationship->$relationship_col);
		}
		
		$relationship_info .= elgg_format_element('td', [], elgg_view('output/url', [
			'text' => elgg_view_icon('remove'),
			'href' => elgg_http_add_url_query_elements('action/developers/entity_explorer_delete', [
				'guid' => $entity->guid,
				'type' => 'relationship',
				'key' => $relationship->id,
			]),
			'confirm' => true,
		]));
		$relationship_info .= '</tr>';
	}
	
	$relationship_info .= '</tbody>';
	$relationship_info .= '</table>';
}

echo elgg_view_module('info', elgg_echo('developers:entity_explorer:info:relationships'), $relationship_info);
