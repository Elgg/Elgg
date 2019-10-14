<?php
/**
 * Edit an existing API key object
 */

use Elgg\WebServices\ApiKeyForm;

$guid = (int) elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid, 'object', ElggApiKey::SUBTYPE, true);
$entity = get_entity($guid);

$title = elgg_echo('edit:object:api_key', [$entity->getDisplayName()]);

$form = new ApiKeyForm($entity);

$content = elgg_view_form('webservices/api_key/edit', [], $form());

if (elgg_is_xhr()) {
	// in the lightbox
	echo elgg_view_module('info', $title, $content);
} else {
	$page_data = elgg_view_layout('default', [
		'title' => $title,
		'content' => $content,
		'filter' => false,
	]);
	
	echo elgg_view_page($title, $page_data);
}
