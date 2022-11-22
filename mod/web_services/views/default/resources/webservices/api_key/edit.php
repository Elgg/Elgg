<?php
/**
 * Edit an existing API key object
 */

$guid = (int) elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid, 'object', ElggApiKey::SUBTYPE, true);
$entity = get_entity($guid);

$title = elgg_echo('edit:object:api_key', [$entity->getDisplayName()]);

$content = elgg_view_form('webservices/api_key/edit', ['sticky_enabled' => true], ['entity' => $entity]);

if (elgg_is_xhr()) {
	// in the lightbox
	echo elgg_view_module('info', $title, $content);
	return;
}

echo elgg_view_page($title, [
	'content' => $content,
	'filter' => false,
]);
