<?php
/**
 * 
 */


function tagcloud_init() {
	add_widget_type('tagcloud', elgg_echo('tagcloud:widget:title'), elgg_echo('tagcloud:widget:description'));

	elgg_extend_view('css','tagcloud/css');
	register_page_handler('tagcloud', 'tagcloud_page_handler');
}

function tagcloud_page_handler($page) {
	global $CONFIG;
	
	switch ($page[0]) {
		default:
			$title = elgg_view_title(elgg_echo('tagcloud:site:title'));
			$tags = display_tagcloud(0, 100, 'tags');
			$body = elgg_view_layout('one_column_with_sidebar', $title . $tags);
			
			page_draw(elgg_echo('tagcloud:site:title'), $body);
			break;
	}
}

register_elgg_event_handler('init', 'system', 'tagcloud_init');
