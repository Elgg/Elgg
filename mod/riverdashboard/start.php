<?php

	/**
	 * Elgg river dashboard plugin
	 * 
	 * @package ElggRiverDash
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

		function riverdashboard_init() {
		
			extend_view('css','riverdashboard/css');
			
			//register_page_handler('dashboard','riverdashboard_dashboard');
			
			add_widget_type('river_widget',elgg_echo('river:widget:title'), elgg_echo('river:widget:description'));
			
		}
		
		function riverdashboard_dashboard() {
			
			include(dirname(__FILE__) . '/index.php');
			
		}

		register_elgg_event_handler('init','system','riverdashboard_init');
		
	// Register actions
		global $CONFIG;
		register_action("riverdashboard/add",false,$CONFIG->pluginspath . "riverdashboard/actions/add.php");
		register_action("riverdashboard/delete",false,$CONFIG->pluginspath . "riverdashboard/actions/delete.php");


?>