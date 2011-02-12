<?php
/**
 * Latest forum posts
 *
 * @uses $vars['entity']
 */

if ($vars['entity']->forum_enable == 'no') {
	return true;
}

$group = $vars['entity'];


$all_link = elgg_view('output/url', array(
	'href' => "pg/discussion/owner/$group->guid",
	'text' => elgg_echo('link:view:all'),
));

$header = "<span class=\"group-widget-viewall\">$all_link</span>";
$header .= '<h3>' . elgg_echo('discussion:group') . '</h3>';


elgg_push_context('widgets');
$options = array(
	'type' => 'object',
	'subtype' => 'groupforumtopic',
	'container_guid' => $group->getGUID(),
	'limit' => 6,
	'full_view' => false,
	'pagination' => false,
);
$content = elgg_list_entities($options);
elgg_pop_context();

if (!$content) {
	$content = '<p>' . elgg_echo('grouptopic:notcreated') . '</p>';
}

$new_link = elgg_view('output/url', array(
	'href' => "pg/discussion/add/" . $group->getGUID(),
	'text' => elgg_echo('groups:addtopic'),
));
$content .= "<span class='elgg-widget-more'>$new_link</span>";

echo elgg_view_module('info', '', $content, array('header' => $header));
