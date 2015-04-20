<?php
/**
 * List comments with optional add form
 *
 * @uses $vars['entity']        ElggEntity
 * @uses $vars['show_add_form'] Display add form or not
 * @uses $vars['id']            Optional id for the div
 * @uses $vars['class']         Optional additional class for the div
 * @uses $vars['limit']         Optional limit value (default is 25)
 * 
 * @todo look into restructuring this so we are not calling elgg_list_entities()
 * in this view
 */

$show_add_form = elgg_extract('show_add_form', $vars, true);
$full_view = elgg_extract('full_view', $vars, true);
$limit = elgg_extract('limit', $vars, get_input('limit', 0));
if (!$limit) {
	$limit = elgg_trigger_plugin_hook('config', 'comments_per_page', [], 25);
}

$attr = [
	'id' => elgg_extract('id', $vars, 'comments'),
	'class' => (array) elgg_extract('class', $vars, []),
];
$attr['class'][] = 'elgg-comments';

// work around for deprecation code in elgg_view()
unset($vars['internalid']);

$content = elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'comment',
	'container_guid' => $vars['entity']->guid,
	'reverse_order_by' => true,
	'full_view' => true,
	'limit' => $limit,
	'preload_owners' => true,
	'distinct' => false,
	'url_fragment' => $attr['id'],
));

if ($show_add_form) {
	$content .= elgg_view_form('comment/save', array(), $vars);
}

echo elgg_format_element('div', $attr, $content);
