<?php

/**
 * Group module (also called a group widget)
 *
 * @uses $vars['title']    The title of the module
 * @uses $vars['content']  The module content
 * @uses $vars['all_link'] A link to list content
 * @uses $vars['add_link'] A link to create content
 */
$group = elgg_get_page_owner_entity();

$all_link = elgg_extract('all_link', $vars);
if ($all_link) {
	$controls = elgg_format_element('span', [
		'class' => 'groups-widget-viewall',
			], $all_link);
}

$add_link = elgg_extract('add_link', $vars);
if ($group->canWriteToContainer() && $add_link) {
	$vars['content'] .= elgg_format_element('span', [
		'class' => 'elgg-widget-more',
	], $add_link);
}

$module = elgg_view_module('info', $vars['title'], $vars['content'], [
	'controls' => $controls,
	'class' => 'elgg-module-group elgg-module-widget card',
		]);

echo elgg_format_element('div', [
	'class' => 'groups-tool-module',
		], $module);
