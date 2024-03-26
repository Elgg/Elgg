<?php
/**
 * Show all the Access Collections the entity is a member of
 *
 * @uses $vars['entity'] the inspected entity
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggEntity) {
	return;
}

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
	
	$rows = [];
	foreach ($acls as $acl) {
		$row = [];
		foreach ($acl_columns as $col_name) {
			$value = $acl->$col_name;
			$title = null;
			
			if ($col_name === 'owner_guid') {
				$value = elgg_view('output/url', [
					'text' => $value,
					'href' => elgg_http_add_url_query_elements('admin/develop_tools/entity_explorer', [
						'guid' => $value,
					]),
				]);
				
				$owner = get_entity($acl->$col_name);
				if ($owner instanceof \ElggEntity) {
					$title = $owner->getDisplayName();
				}
			} else {
				$value = elgg_view('output/text', ['value' => $value]);
			}
			
			$row[] = elgg_format_element('td', [
				'title' => $title,
				'class' => in_array($col_name, ['id', 'owner_guid']) ? 'elgg-nowrap' : null,
			], $value);
		}
		
		$rows[] = elgg_format_element('tr', [], implode('', $row));
	}
	
	$result .= elgg_format_element('tbody', [], implode(PHP_EOL, $rows));
	$result .= '</table>';
}

echo elgg_view_module('info', elgg_echo('developers:entity_explorer:info:acl_memberships'), $result);
