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

if ($group->canWriteToContainer() && isset($vars['add_link'])) {
    $header = "<span class='groups-widget-add'>{$vars['add_link']}</span>";
}
$header .= '<h3>' . $vars['all_link'] . '</h3>';

echo '<li>';
echo elgg_view_module('info', '', $vars['content'], array(
	'header' => $header,
	'class' => 'elgg-module-group',
));
echo '</li>';
