<?php
/**
 * Tagcloud plugin
 */

elgg_register_event_handler('init', 'system', 'tagcloud_init');

function tagcloud_init() {
	elgg_extend_view('theme_sandbox/components', 'tagcloud/theme_sandbox/component');
	elgg_extend_view('css/elgg', 'css/elgg/tagcloud.css');
	
	elgg_register_page_handler('tags', 'tagcloud_tags_page_handler');
	
	elgg_register_widget_type('tagcloud', elgg_echo('tagcloud:widget:title'), elgg_echo('tagcloud:widget:description'));
}


/**
 * Page hander for sitewide tag cloud
 *
 * @param array $page Page array
 *
 * @return bool
 */
function tagcloud_tags_page_handler($page) {

	$title = elgg_echo('tagcloud:site_cloud');
	$options = array(
		'threshold' => 0,
		'limit' => 100,
		'tag_name' => 'tags',
	);

	$content = elgg_view_tagcloud($options);

	$body = elgg_view_layout('one_sidebar', array(
		'title' => $title,
		'content' => $content,
	));

	echo elgg_view_page($title, $body);
	return true;
}