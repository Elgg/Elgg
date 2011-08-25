<?php
/**
 * Group pages
 *
 * @package ElggPages
 */


$group = elgg_get_page_owner_entity();

if ($group->pages_enable == "no") {
	return true;
}

$all_link = elgg_view('output/url', array(
	'href' => "pages/group/$group->guid/all",
	'text' => elgg_echo('link:view:all'),
));


elgg_push_context('widgets');
$options = array(
	'type' => 'object',
	'subtype' => 'page_top',
	'container_guid' => elgg_get_page_owner_guid(),
	'limit' => 6,
	'full_view' => false,
	'pagination' => false,
);
$content = elgg_list_entities($options);
elgg_pop_context();

if (!$content) {
	$content = '<p>' . elgg_echo('pages:none') . '</p>';
}

$new_link = elgg_view('output/url', array(
	'href' => "pages/add/$group->guid",
	'text' => elgg_echo('pages:add'),
));

echo elgg_view('groups/profile/module', array(
	'title' => elgg_echo('pages:group'),
	'content' => $content,
	'all_link' => $all_link,
	'add_link' => $new_link,
));
