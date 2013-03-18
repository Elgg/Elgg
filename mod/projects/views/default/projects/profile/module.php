<?php
/**
 * Group module (also called a project widget)
 *
 * @uses $vars['title']    The title of the module
 * @uses $vars['content']  The module content
 * @uses $vars['all_link'] A link to list content
 * @uses $vars['add_link'] A link to create content
 */

$project = elgg_get_page_owner_entity();

$header = "<span class=\"projects-widget-viewall\">{$vars['all_link']}</span>";
$header .= '<h3>' . $vars['title'] . '</h3>';

if ($project->canWriteToContainer() && isset($vars['add_link'])) {
	$vars['content'] .= "<span class='elgg-widget-more'>{$vars['add_link']}</span>";
}

echo '<li>';
echo elgg_view_module('info', '', $vars['content'], array(
	'header' => $header,
	'class' => 'elgg-module-project',
));
echo '</li>';
