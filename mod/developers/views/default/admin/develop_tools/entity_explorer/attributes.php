<?php
$entity = elgg_extract('entity', $vars);

$entity_rows = ['type', 'subtype', 'owner_guid', 'container_guid', 'access_id', 'time_created', 'time_updated', 'last_action', 'enabled'];

$entity_info = '<table class="elgg-table">';

foreach ($entity_rows as $entity_row) {
	$value = elgg_view('output/text', ['value' => $entity->$entity_row]);
	
	$entity_info .= '<tr>';
	$entity_info .= '<td>' . $entity_row . '</td><td>' . $value . '</td>';
	$entity_info .= '</tr>';
}

$entity_info .= '</table>';
	
echo elgg_view_module('info', elgg_echo('developers:entity_explorer:info:attributes'), $entity_info);
