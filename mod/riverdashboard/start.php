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
			
			register_page_handler('dashboard','riverdashboard_dashboard');
			
			
			
		}
		
		function riverdashboard_dashboard() {
			
			include(dirname(__FILE__) . '/index.php');
			
		}

		register_plugin_hook('init','system','riverdashboard_init');
		
	// Register actions
		global $CONFIG;
		register_action("riverdashboard/add",false,$CONFIG->pluginspath . "riverdashboard/actions/add.php");
		register_action("riverdashboard/delete",false,$CONFIG->pluginspath . "riverdashboard/actions/delete.php");


?>