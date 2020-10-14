<?php
/**
 * Edit page
 */

use Elgg\Exceptions\Http\EntityPermissionsException;

$enable_editing = (bool) elgg_get_plugin_setting('enable_editing', 'thewire');
	
if(!$enable_editing) {
	throw new EntityPermissionsException();
}

$guid = elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid, 'object', 'thewire');

/* @var $post ElggWire */
$post = get_entity($guid);

elgg_push_entity_breadcrumbs($post, true);

$content = elgg_view_form('thewire/add', [
	'class' => 'thewire-form',
], [
	'guid' => $guid,
]);
$content .= elgg_view('input/urlshortener');

echo elgg_view_page(elgg_echo('edit'), [
	'content' => $content,
	'filter_id' => 'thewire/edit',
]);
