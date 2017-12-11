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

$header = '<h3>' . $vars['title'] . '</h3>';
$header .= "<span class=\"groups-widget-viewall\">{$vars['all_link']}</span>";

if ($group->canWriteToContainer() && isset($vars['add_link'])) {
	$footer = "<span class='elgg-widget-more'>{$vars['add_link']}</span>";
}

echo '<li>';
echo elgg_view_module('info', '', $vars['content'], [
	'header' => $header,
	'class' => 'elgg-module-group',
	'footer' => $footer,
]);
echo '</li>';
