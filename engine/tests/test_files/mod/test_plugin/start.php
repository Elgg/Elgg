<?php

/**
 * Init test_plugin plugin
 *
 * @return void
 */
function test_plugin_init() {

}

return function() {
	elgg_register_event_handler('init', 'system', 'test_plugin_init');
};
