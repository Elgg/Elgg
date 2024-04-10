<?php
/**
 * Show the entity attributes
 *
 * @uses $vars['entity'] the inspected entity
 */

use Elgg\Values;

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggEntity) {
	return;
}

$rows = [];
foreach (\ElggEntity::PRIMARY_ATTR_NAMES as $entity_row) {
	$row = [];
	$row[] = elgg_format_element('td', [], $entity_row);
	
	$title = null;
	$is_text = true;
	$value = $entity->$entity_row;
	switch ($entity_row) {
		case 'guid':
			continue(2);
		case 'owner_guid':
		case 'container_guid':
			$is_text = false;
			$value = elgg_view('output/url', [
				'text' => $value,
				'href' => elgg_http_add_url_query_elements('admin/develop_tools/entity_explorer', [
					'guid' => $value,
				]),
			]);
			
			$owner_container = get_entity($entity->$entity_row);
			if ($owner_container instanceof \ElggEntity) {
				$title = $owner_container->getDisplayName();
			}
			break;
		case 'access_id':
			$title = elgg_get_readable_access_level($value);
			break;
		case 'time_created':
		case 'time_deleted':
		case 'time_updated':
		case 'last_action':
			$title = Values::normalizeTime($value)->formatLocale(elgg_echo('friendlytime:date_format'));
			break;
	}
	
	if ($is_text) {
		$value = elgg_view('output/text', ['value' => $value]);
	}
	
	$row[] = elgg_format_element('td', [], $value);
	
	$rows[] = elgg_format_element('tr', ['title' => $title], implode('', $row));
}

$entity_info = elgg_format_element('table', ['class' => 'elgg-table'], implode(PHP_EOL, $rows));
	
echo elgg_view_module('info', elgg_echo('developers:entity_explorer:info:attributes'), $entity_info);
