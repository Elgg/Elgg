<?php
elgg_register_event_handler('init', 'system', 'banner_init');

function banner_init() {
	
	
	elgg_extend_view("page/elements/body", "banner/banner", 0);
	elgg_register_action("banner/banner", elgg_get_plugins_path() . "banner/actions/banner.php");
	elgg_extend_view('css','banner/css');
	
}


