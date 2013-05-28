<?php
/**
 * Elgg theme plugin
 *
 * @package Coopfunding
 * @subpackage Theme
 */
 
elgg_register_event_handler('init','system','coopfunding_theme_init');
 
function coopfunding_theme_init() {

	elgg_register_event_handler('pagesetup', 'system', function() {

		elgg_unregister_menu_item('footer', 'elgg');
		
		if(elgg_is_active_plugin('externalpages')) {
			elgg_register_menu_item('site', array(
				'name' => 'about',
				'href' => 'about',
				'text' => elgg_echo('expages:about'),
				'priority' => 101,
			));
		}

	}, 1001);

	elgg_extend_view('css/elgg', 'coopfunding_theme/css');

}
