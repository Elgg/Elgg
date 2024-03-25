<?php
/**
 * Show all metadata belonging to an entity
 *
 * @uses $vars['entity'] the entity to inspect
 */

use Elgg\Values;

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggEntity) {
	return;
}

$entity_metadata = elgg_get_metadata(['guid' => $entity->guid, 'limit' => false]);

if (empty($entity_metadata)) {
	$metadata_info = elgg_echo('notfound');
} else {
	$md_columns = ['id', 'name', 'value', 'value_type', 'time_created'];
	
	$metadata_info = '<table class="elgg-table">';
	$metadata_info .= '<thead><tr>';
	foreach ($md_columns as $md_col) {
		$metadata_info .= elgg_format_element('th', [], $md_col);
	}
	
	$metadata_info .= '<th>&nbsp;</th>';
	$metadata_info .= '</tr></thead>';
	
	$rows = [];
	foreach ($entity_metadata as $md) {
		$row = [];
		
		foreach ($md_columns as $md_col) {
			$value = $md->$md_col;
			$title = null;
			$class = 'elgg-nowrap';
			
			switch ($md_col) {
				case 'time_created':
					$title = Values::normalizeTime($value)->formatLocale(elgg_echo('friendlytime:date_format'));
					break;
				case 'name':
					$class = null;
					break;
				case 'value':
					if (is_bool($value)) {
						$value = $value ? 'true' : 'false';
					}
					
					$class = null;
					break;
			}
			
			$value = elgg_view('output/text', ['value' => $value]);
			
			$row[] = elgg_format_element('td', ['title' => $title, 'class' => $class], $value);
		}
		
		$row[] = elgg_format_element('td', [], elgg_view('output/url', [
			'icon' => 'remove',
			'text' => false,
			'title' => elgg_echo('delete'),
			'href' => elgg_http_add_url_query_elements('action/developers/entity_explorer_delete', [
				'guid' => $entity->guid,
				'type' => 'metadata',
				'key' => $md->id,
			]),
			'confirm' => true,
		]));
		
		$rows[] = elgg_format_element('tr', [], implode('', $row));
	}
	
	$metadata_info .= elgg_format_element('tbody', [], implode(PHP_EOL, $rows));
	$metadata_info .= '</table>';
}

echo elgg_view_module('info', elgg_echo('developers:entity_explorer:info:metadata'), $metadata_info);
