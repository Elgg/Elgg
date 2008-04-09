<?php
	/**
	 * Elgg export test
	 * 
	 * @package ElggDevTools
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey <marcus@marcus-povey.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	function exporttest_init($event, $object_type, $object = null) {
		
		global $CONFIG;
			
		add_menu("Export GUID",$CONFIG->wwwroot . "mod/exporttest/",array(
				menu_item("The GUID Exporter",$CONFIG->wwwroot."mod/exporttest/"),
		));
	}

	
	// Make sure test_init is called on initialisation
	register_event_handler('init','system','exporttest_init');
?>