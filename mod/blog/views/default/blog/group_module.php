<?php
/**
 * Group blog module
 */

$group = elgg_get_page_owner_entity();

if ($group->blog_enable == "no") {
	return true;
}

$all_link = elgg_view('output/url', array(
	'href' => "blog/group/$group->guid/all",
	'text' => elgg_echo('link:view:all'),
	'is_trusted' => true,
));

elgg_push_context('widgets');
$options = array(
	'type' => 'object',
	'subtype' => 'blog',
	'container_guid' => elgg_get_page_owner_guid(),
	'limit' => 6,
	'full_view' => false,
	'pagination' => false,
	'no_results' => elgg_echo('blog:none'),
	'distinct' => false,
);
$content = elgg_list_entities($options);
elgg_pop_context();

$new_link = elgg_view('output/url', array(
	'href' => "blog/add/$group->guid",
	'text' => elgg_echo('blog:write'),
	'is_trusted' => true,
));

echo elgg_view('groups/profile/module', array(
	'title' => elgg_echo('blog:group'),
	'content' => $content,
	'all_link' => $all_link,
	'add_link' => $new_link,
));
