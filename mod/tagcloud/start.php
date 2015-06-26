<?php
/**
 * Tagcloud plugin
 */

elgg_register_event_handler('init', 'system', 'tagcloud_init');

function tagcloud_init() {
	elgg_extend_view('theme_sandbox/components', 'tagcloud/theme_sandbox/component');
	elgg_extend_view('elgg.css', 'elgg/tagcloud.css');
	
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
	echo elgg_view_resource('tagcloud');
	return true;
}