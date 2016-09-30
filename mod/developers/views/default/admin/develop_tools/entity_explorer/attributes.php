<?php
$entity = elgg_extract('entity', $vars);

$entity_rows = ['type', 'subtype', 'owner_guid', 'site_guid', 'container_guid', 'access_id', 'time_created', 'time_updated', 'last_action', 'enabled'];

if ($entity instanceof ElggUser) {
	$entity_rows = array_merge($entity_rows, ['name', 'username', 'email', 'language', 'banned', 'admin', 'last_action', 'prev_last_action', 'last_login', 'prev_last_login']);
}

if ($entity instanceof ElggGroup) {
	$entity_rows = array_merge($entity_rows, ['name', 'description']);
}

if ($entity instanceof ElggSite) {
	$entity_rows = array_merge($entity_rows, ['name', 'description', 'url']);
}

$entity_info = '<table class="elgg-table">';

foreach ($entity_rows as $entity_row) {
	
	$value = elgg_view('output/text', ['value' => $entity->$entity_row]);
	
	$entity_info .= '<tr>';
	$entity_info .= '<td>' . $entity_row . '</td><td>' . $value . '</td>';
	$entity_info .= '</tr>';
}

$entity_info .= '</table>';
	
echo elgg_view_module('inline', elgg_echo('developers:entity_explorer:info:attributes'), $entity_info);
