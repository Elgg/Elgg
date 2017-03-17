<?php

echo '<p>' . elgg_echo('developers:entity_explorer:help') . '</p>';

echo elgg_view_form('developers/entity_explorer', [
	'action' => 'admin/develop_tools/entity_explorer',
	'method' => 'GET',
	'disable_security' => true,
]);

$guid = get_input('guid');
if ($guid === null) {
	return;
}

$show_hidden = access_show_hidden_entities(true);
$entity = get_entity($guid);
if (!$entity) {
	echo elgg_echo('notfound');
	return;
}

// Entity Information
echo elgg_view('admin/develop_tools/entity_explorer/attributes', ['entity' => $entity]);

// Metadata Information
echo elgg_view('admin/develop_tools/entity_explorer/metadata', ['entity' => $entity]);

// Relationship Information
echo elgg_view('admin/develop_tools/entity_explorer/relationships', ['entity' => $entity]);

// Private Settings Information
echo elgg_view('admin/develop_tools/entity_explorer/private_settings', ['entity' => $entity]);

access_show_hidden_entities($show_hidden);

echo elgg_view('output/url', [
	'text' => elgg_echo('developers:entity_explorer:delete_entity'),
	'href' => 'action/developers/entity_explorer_delete?guid=' . $entity->guid . '&type=entity&key=' . $entity->guid,
	'confirm' => true,
	'class' => 'elgg-button elgg-button-submit',
]);
