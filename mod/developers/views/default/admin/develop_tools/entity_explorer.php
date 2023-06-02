<?php

echo elgg_format_element('p', [], elgg_echo('developers:entity_explorer:help'));

echo elgg_view_form('developers/entity_explorer', [
	'action' => 'admin/develop_tools/entity_explorer',
	'method' => 'GET',
	'disable_security' => true,
	'class' => 'mbm',
]);

$guid = (int) get_input('guid');
if (empty($guid)) {
	return;
}

echo elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function() use ($guid) {
	$entity = get_entity($guid);
	if (!$entity) {
		return elgg_echo('notfound');
	}
	
	// Entity Information
	$result = elgg_view('admin/develop_tools/entity_explorer/attributes', ['entity' => $entity]);
	
	// Metadata Information
	$result .= elgg_view('admin/develop_tools/entity_explorer/metadata', ['entity' => $entity]);
	
	// Relationship Information
	$result .= elgg_view('admin/develop_tools/entity_explorer/relationships', ['entity' => $entity]);
	
	// Owned ACLs
	$result .= elgg_view('admin/develop_tools/entity_explorer/owned_acls', ['entity' => $entity]);
	
	// ACL membership
	$result .= elgg_view('admin/develop_tools/entity_explorer/acl_memberships', ['entity' => $entity]);
	
	// Button bar
	$result .= elgg_view_menu('entity_explorer', [
		'entity' => $entity,
		'class' => 'elgg-menu-hz',
	]);
	
	return $result;
});
