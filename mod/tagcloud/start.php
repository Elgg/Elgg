<?php
/**
 * 
 */


function tagcloud_init() {
	add_widget_type('tagcloud', elgg_echo('tagcloud:widget:title'), elgg_echo('tagcloud:widget:description'));
	elgg_extend_view('css/screen', 'tagcloud/css');
}

elgg_register_event_handler('init', 'system', 'tagcloud_init');
