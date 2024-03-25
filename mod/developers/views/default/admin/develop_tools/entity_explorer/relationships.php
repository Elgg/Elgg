<?php
/**
 * List all the relationships of the given entity
 *
 * @uses $vars['entity'] the entity to inspect
 */

use Elgg\Database\Clauses\OrderByClause;
use Elgg\Database\QueryBuilder;
use Elgg\Values;

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggEntity) {
	return;
}

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
	
	$rows = [];
	foreach ($entity_relationships as $relationship) {
		$row = [];
		
		foreach ($relationship_columns as $relationship_col) {
			$value = $relationship->$relationship_col;
			$title = null;
			$class = 'elgg-nowrap';
			$is_text = true;
			
			switch ($relationship_col) {
				case 'guid_one':
				case 'guid_two':
					$is_text = false;
					$value = elgg_view('output/url', [
						'text' => $value,
						'href' => elgg_http_add_url_query_elements('admin/develop_tools/entity_explorer', [
							'guid' => $value,
						]),
					]);
					
					$rel_entity = get_entity($relationship->$relationship_col);
					if ($rel_entity instanceof \ElggEntity) {
						$title = $rel_entity->getDisplayName();
					}
					break;
				case 'time_created':
					$title = Values::normalizeTime($value)->formatLocale(elgg_echo('friendlytime:date_format'));
					break;
				case 'relationship':
					$class = null;
					break;
			}
			
			if ($is_text) {
				$value = elgg_view('output/text', ['value' => $value]);
			}
			
			$row[] = elgg_format_element('td', ['title' => $title, 'class' => $class], $value);
		}
		
		$row[] = elgg_format_element('td', [], elgg_view('output/url', [
			'icon' => 'remove',
			'text' => false,
			'title' => elgg_echo('delete'),
			'href' => elgg_http_add_url_query_elements('action/developers/entity_explorer_delete', [
				'guid' => $entity->guid,
				'type' => 'relationship',
				'key' => $relationship->id,
			]),
			'confirm' => true,
		]));
		
		$rows[] = elgg_format_element('tr', [], implode('', $row));
	}
	
	$relationship_info .= elgg_format_element('tbody', [], implode(PHP_EOL, $rows));
	$relationship_info .= '</table>';
}

echo elgg_view_module('info', elgg_echo('developers:entity_explorer:info:relationships'), $relationship_info);
