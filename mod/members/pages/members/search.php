<?php
/**
 * Members search page
 *
 */

if ($vars['search_type'] == 'tag') {
	$tag = get_input('tag');

	$title = elgg_echo('members:title:searchtag', array($tag));

	$options = array();
	$options['query'] = $tag;
	$options['type'] = "user";
	$options['offset'] = $offset;
	$options['limit'] = $limit;
	$results = elgg_trigger_plugin_hook('search', 'tags', $options, array());
	$count = $results['count'];
	$users = $results['entities'];
	$content = elgg_view_entity_list($users, array(
		'count' => $count,
		'offset' => $offset,
		'limit' => $limit,
		'full_view' => false,
		'list_type_toggle' => false,
		'pagination' => true,
	));
} else {
	$name = sanitize_string(get_input('name'));

	$title = elgg_echo('members:title:searchname', array($name));

	$db_prefix = elgg_get_config('dbprefix');
	$params = array(
		'type' => 'user',
		'full_view' => false,
		'joins' => array("JOIN {$db_prefix}users_entity u ON e.guid=u.guid"),
		'wheres' => array("(u.name LIKE \"%{$name}%\" OR u.username LIKE \"%{$name}%\")"),
	);
	$content .= elgg_list_entities($params);
}

$params = array(
	'title' => $title,
	'content' => $content,
	'sidebar' => elgg_view('members/sidebar'),
);

$body = elgg_view_layout('one_sidebar', $params);

echo elgg_view_page($title, $body);
