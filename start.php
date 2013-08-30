<?php

	function lazy_hover_init() {
		elgg_extend_view("js/elgg", "js/lazy_hover/site");
		
		elgg_register_page_handler("lazy_hover", "lazy_hover_page_handler");
		
		// extend public pages
		elgg_register_plugin_hook_handler('public_pages', 'walled_garden', 'lazy_hover_public_pages');
		
	}
	
	function lazy_hover_page_handler($page) {
		include(dirname(__FILE__) . "/pages/lazy_hover.php");
		return true;
	}
	
	/**
	 *
	 * Extend public pages
	 * @param unknown_type $hook_name
	 * @param unknown_type $entity_type
	 * @param unknown_type $return_value
	 * @param unknown_type $parameters
	 */
	function lazy_hover_public_pages($hook_name, $entity_type, $return_value, $params){
		$return = $return_value;
		if(is_array($return)){
			$return[] = "lazy_hover";
		}
		return $return;
	}

	// register default elgg events
	elgg_register_event_handler("init", "system", "lazy_hover_init");
	