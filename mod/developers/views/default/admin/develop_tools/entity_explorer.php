<?php

echo '<p>' . elgg_echo('developers:entity_explorer:help') . '</p>';

echo elgg_view_form('developers/entity_explorer', [
	'action' => 'admin/develop_tools/entity_explorer',
	'method' => 'GET',
	'disable_security' => true,
	'class' => 'mbm',
]);

$guid = get_input('guid');
if ($guid === null) {
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
	
	// Private Settings Information
	$result .= elgg_view('admin/develop_tools/entity_explorer/private_settings', ['entity' => $entity]);
	
	// Owned ACLs
	$result .= elgg_view('admin/develop_tools/entity_explorer/owned_acls', ['entity' => $entity]);
	
	// ACL membership
	$result .= elgg_view('admin/develop_tools/entity_explorer/acl_memberships', ['entity' => $entity]);
	
	$url = $entity->getURL();
	if (!empty($url)) {
		$result .= elgg_view_url($url, elgg_echo('developers:entity_explorer:view_entity'), [
			'class' => 'elgg-button elgg-button-action',
		]);
	}
	$result .= elgg_view('output/url', [
		'text' => elgg_echo('developers:entity_explorer:delete_entity'),
		'href' => elgg_generate_action_url('developers/entity_explorer_delete', [
			'guid' => $guid,
			'type' => 'entity',
			'key' => $guid,
		]),
		'confirm' => elgg_echo('deleteconfirm'),
		'class' => 'elgg-button elgg-button-delete',
	]);
	
	return $result;
});
