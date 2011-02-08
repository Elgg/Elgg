<?php
/**
 * Tagcloud plugin
 */

elgg_register_event_handler('init', 'system', 'tagcloud_init');

function tagcloud_init() {
	elgg_register_widget_type('tagcloud', elgg_echo('tagcloud:widget:title'), elgg_echo('tagcloud:widget:description'));
}
