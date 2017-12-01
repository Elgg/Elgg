<?php
/**
 * List comments with optional add form
 *
 * @uses $vars['entity']        ElggEntity
 * @uses $vars['show_add_form'] Display add form or not
 * @uses $vars['id']            Optional id for the div
 * @uses $vars['class']         Optional additional class for the div
 * @uses $vars['limit']         Optional limit value (default is 25)
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggEntity) {
	return;
}

$show_add_form = elgg_extract('show_add_form', $vars, true);

$limit = elgg_extract('limit', $vars, get_input('limit', 0));
if (!$limit) {
	$limit = elgg_trigger_plugin_hook('config', 'comments_per_page', [], 25);
}

$attr = [
	'id' => elgg_extract('id', $vars, 'comments'),
	'class' => elgg_extract_class($vars, 'elgg-comments'),
];

$content = '';
if ($show_add_form && $entity->canComment()) {
	$content .= elgg_view_form('comment/save', [], $vars);
}

$content .= elgg_list_entities([
	'type' => 'object',
	'subtype' => 'comment',
	'container_guid' => $entity->guid,
	'full_view' => true,
	'limit' => $limit,
	'preload_owners' => true,
	'distinct' => false,
	'url_fragment' => $attr['id'],
]);

if (empty($content)) {
	return;
}

echo elgg_format_element('div', $attr, $content);
