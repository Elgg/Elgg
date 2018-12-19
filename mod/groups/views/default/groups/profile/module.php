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

$title = elgg_extract('title', $vars);

$menu = '';
$all_link = elgg_extract('all_link', $vars);
if ($all_link) {
	$menu = elgg_format_element('span', [
		'class' => 'groups-widget-viewall',
	], $all_link);
}

$footer = '';
if ($group->canWriteToContainer() && isset($vars['add_link'])) {
	$footer = elgg_format_element('span', [
		'class' => 'elgg-widget-more',
	], elgg_extract('add_link', $vars));
}

echo elgg_view_module('info', $title, $vars['content'], [
	'menu' => $menu,
	'class' => 'elgg-module-group',
	'footer' => $footer,
]);
