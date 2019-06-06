<?php

/** @var \ElggEntity $entity */
$entity = elgg_extract('entity', $vars);

$acls = _elgg_services()->accessCollections->getCollectionsByMember($entity->guid);

if (empty($acls)) {
	$result = elgg_echo('notfound');
} else {
	$acl_columns = ['id', 'owner_guid', 'name', 'subtype'];

	$result = '<table class="elgg-table">';
	$result .= '<thead><tr>';
	foreach ($acl_columns as $col_name) {
		$result .= '<th>' . $col_name . '</th>';
	}
	$result .= '</tr></thead>';
	
	foreach ($acls as $acl) {
		$result .= '<tr>';
		foreach ($acl_columns as $col_name) {
			$result .= '<td>' . $acl->$col_name . '</td>';
		}
		$result .= '</tr>';
	}
	$result .= '</table>';
}

echo elgg_view_module('info', elgg_echo('developers:entity_explorer:info:acl_memberships'), $result);
