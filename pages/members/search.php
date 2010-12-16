<?php
/**
 * Members search page
 *
 */

if ($vars['search_type'] == 'tag') {
	$tag = get_input('tag');

	$title = elgg_echo('members:title:searchtag', array($tag));
	$content = elgg_view_title($title);

	$options = array();
	$options['query'] = $tag;
	$options['type'] = "user";
	$options['offset'] = $offset;
	$options['limit'] = $limit;
	$results = elgg_trigger_plugin_hook('search', 'tags', $options, array());
	$count = $results['count'];
	$users = $results['entities'];
	$content .= elgg_view_entity_list($users, $count, $offset, $limit, false, false, true);
} else {
	$name = get_input('name');

	$title = elgg_echo('members:title:searchname', array($name));
	$content = elgg_view_title($title);

	elgg_set_context('search');
	$content .= list_user_search($name);
}

$params = array(
	'content' => $content,
	'sidebar' => elgg_view('core/members/sidebar'),
);

$body = elgg_view_layout('one_sidebar', $params);

echo elgg_view_page($title, $body);
