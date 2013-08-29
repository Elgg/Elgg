<?php

	function lazy_hover_init() {
		// elgg_register_plugin_hook_handler('register', 'menu:user_hover', "lazy_hover_register_hover_menu", 1);
		elgg_extend_view("js/elgg", "js/lazy_hover/site");
		
		elgg_register_page_handler("lazy_hover", "lazy_hover_page_handler");
		
	}
	
	function lazy_hover_page_handler($page) {
		include(dirname(__FILE__) . "/pages/lazy_hover.php");
		return true;
	}

	// register default elgg events
	elgg_register_event_handler("init", "system", "lazy_hover_init");
	