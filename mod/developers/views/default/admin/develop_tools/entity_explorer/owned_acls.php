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
		$result .= '<th>' . $col_name . '</th>';
	}
	$result .= '<th>&nbsp;</th>';
	$result .= '</tr></thead>';
	
	foreach ($acls as $acl) {
		$result .= '<tr>';
		foreach ($acl_columns as $col_name) {
			$result .= '<td>' . $acl->$col_name . '</td>';
		}
		$result .= '<td>' . elgg_view('output/url', [
			'text' => elgg_view_icon('remove'),
			'href' => elgg_http_add_url_query_elements('action/developers/entity_explorer_delete', [
				'guid' => $entity->guid,
				'type' => 'acl',
				'key' => $acl->id,
			]),
			'confirm' => true,
		]) . '</td>';
		$result .= '</tr>';
	}
	$result .= '</table>';
}

echo elgg_view_module('info', elgg_echo('developers:entity_explorer:info:owned_acls'), $result);
