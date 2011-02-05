<?php
/**
 * Group blog module
 */

$group = elgg_get_page_owner_entity();

if ($group->blog_enable == "no") {
	return true;
}

$all_link = elgg_view('output/url', array(
	'href' => "pg/blog/group/$group->guid/owner",
	'text' => elgg_echo('link:view:all'),
));

$header = "<span class=\"group-widget-viewall\">$all_link</span>";
$header .= '<h3>' . elgg_echo('blog:group') . '</h3>';


elgg_push_context('widgets');
$options = array(
	'type' => 'object',
	'subtype' => 'blog',
	'container_guid' => elgg_get_page_owner_guid(),
	'limit' => 6,
	'full_view' => false,
	'pagination' => false,
);
$content = elgg_list_entities($options);
elgg_pop_context();

if (!$content) {
	$content = '<p>' . elgg_echo('blog:none') . '</p>';
}

$new_link = elgg_view('output/url', array(
	'href' => "pg/blog/add/$group->guid",
	'text' => elgg_echo('blog:write'),
));
$content .= "<span class='elgg-widget-more'>$new_link</span>";


$params = array(
	'header' => $header,
	'body' => $content,
	'class' => 'elgg-module-info',
);
echo elgg_view('layout/objects/module', $params);
