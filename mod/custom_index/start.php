<?php

	/**
	 * Elgg custom index page
	 * 
	 * @package ElggIndexCustom
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	 
    function indexCustom_init() {
	
        // Extend system CSS with our own styles
				elgg_extend_view('css','custom_index/css');
				
       // Replace the default index page
			register_plugin_hook('index','system','custom_index');
			
				
    }
    
    function custom_index() {
			
			if (!include_once(dirname(__FILE__) . "/index.php")) return false;
			return true;
			
		}


    // Make sure the
		    register_elgg_event_handler('init','system','indexCustom_init');

?>