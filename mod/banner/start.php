<?php
elgg_register_event_handler('init', 'system', 'banner_init');

function banner_init() {
		
	elgg_extend_view("page/elements/body", "banner/banner", 0);		
	elgg_extend_view('css/elgg','banner/css');
	elgg_extend_view('js/elgg', 'banner/js');

	
	
}





