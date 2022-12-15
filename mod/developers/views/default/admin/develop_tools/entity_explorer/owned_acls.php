<?php

/** @var \ElggEntity $entity */
$entity = elgg_extract('entity', $vars);

$acls = $entity->getOwnedAccessCollections();

if (empty($acls)) {
	$result = elgg_echo('notfound');
} else {
	$acl_columns = ['id', 'name', 'subtype'];

	$result = '<table class="elgg-table">';
	$result .= '<thead><tr>';
	foreach ($acl_columns as $col_name) {
		$result .= elgg_format_element('th', [], $col_name);
	}
	
	$result .= '<th>&nbsp;</th>';
	$result .= '</tr></thead>';
	$result .= '<tbody>';
	
	foreach ($acls as $acl) {
		$result .= '<tr>';
		foreach ($acl_columns as $col_name) {
			$result .= elgg_format_element('td', [], $acl->$col_name);
		}
		
		$result .= elgg_format_element('td', [], elgg_view('output/url', [
			'text' => elgg_view_icon('remove'),
			'href' => elgg_http_add_url_query_elements('action/developers/entity_explorer_delete', [
				'guid' => $entity->guid,
				'type' => 'acl',
				'key' => $acl->id,
			]),
			'confirm' => true,
		]));
		$result .= '</tr>';
	}
	
	$result .= '</tbody>';
	$result .= '</table>';
}

echo elgg_view_module('info', elgg_echo('developers:entity_explorer:info:owned_acls'), $result);
