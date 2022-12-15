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
		$result .= elgg_format_element('th', [], $col_name);
	}
	
	$result .= '</tr></thead>';
	$result .= '<tbody>';
	
	foreach ($acls as $acl) {
		$result .= '<tr>';
		foreach ($acl_columns as $col_name) {
			$result .= elgg_format_element('td', [], $acl->$col_name);
		}
		
		$result .= '</tr>';
	}
	
	$result .= '</tbody>';
	$result .= '</table>';
}

echo elgg_view_module('info', elgg_echo('developers:entity_explorer:info:acl_memberships'), $result);
