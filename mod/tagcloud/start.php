<?php
/**
 * Tagcloud plugin
 */

/**
 * Tagcloud init
 *
 * @return void
 */
function tagcloud_init() {
	elgg_extend_view('theme_sandbox/components', 'tagcloud/theme_sandbox/component');
	elgg_extend_view('elgg.css', 'elgg/tagcloud.css');
}

return function() {
	elgg_register_event_handler('init', 'system', 'tagcloud_init');
};
