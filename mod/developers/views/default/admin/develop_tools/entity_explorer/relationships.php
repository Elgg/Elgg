<?php

$entity = elgg_extract('entity', $vars);

$entity_relationships = get_entity_relationships($entity->guid);

if (empty($entity_relationships)) {
	$relationship_info = elgg_echo('notfound');
} else {
	$relationship_columns = ['id', 'time_created', 'guid_one', 'relationship', 'guid_two'];

	$relationship_info = '<table class="elgg-table">';
	$relationship_info .= '<thead><tr>';
	foreach ($relationship_columns as $relationship_col) {
		$relationship_info .= '<th>' . $relationship_col . '</th>';
	}
	$relationship_info .= '<th>&nbsp;</th>';
	$relationship_info .= '</tr></thead>';
	
	foreach ($entity_relationships as $relationship) {
		$relationship_info .= '<tr>';
		foreach ($relationship_columns as $relationship_col) {
			$relationship_info .= '<td>' . $relationship->$relationship_col . '</td>';
		}
		$relationship_info .= '<td>' . elgg_view('output/url', [
			'text' => elgg_view_icon('remove'),
			'href' => elgg_http_add_url_query_elements('action/developers/entity_explorer_delete', [
				'guid' => $entity->guid,
				'type' => 'relationship',
				'key' => $relationship->id,
			]),
			'confirm' => true,
		]) . '</td>';
		$relationship_info .= '</tr>';
	}
	$relationship_info .= '</table>';
}
echo elgg_view_module('info', elgg_echo('developers:entity_explorer:info:relationships'), $relationship_info);
