<?php
/**
 * Group file module
 */

$group = elgg_get_page_owner();

if ($group->file_enable == "no") {
	return true;
}

$all_link = elgg_view('output/url', array(
	'href' => "pg/file/group/$group->guid/owner",
	'text' => elgg_echo('link:view:all'),
));

$header = "<span class=\"group-widget-viewall\">$all_link</span>";
$header .= '<h3>' . elgg_echo('file:group') . '</h3>';


elgg_push_context('widgets');
$options = array(
	'type' => 'object',
	'subtype' => 'file',
	'container_guid' => elgg_get_page_owner_guid(),
	'limit' => 6,
	'full_view' => false,
	'pagination' => false,
);
$content = elgg_list_entities($options);
elgg_pop_context();

if (!$content) {
	$content = '<p>' . elgg_echo('file:none') . '</p>';
}

$new_link = elgg_view('output/url', array(
	'href' => "pg/file/add/$group->guid",
	'text' => elgg_echo('file:add'),
));
$content .= "<span class='elgg-widget-more'>$new_link</span>";


$params = array(
	'header' => $header,
	'body' => $content,
);
echo elgg_view('layout/objects/module', $params);
