<?php

	/**
	 * Elgg sites
	 * Functions to manage multiple or single sites in an Elgg install
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	/**
	 * Initialise site handling
	 *
	 * Called at the beginning of system running, to set the ID of the current site.
	 * This is 0 by default, but plugins may alter this behaviour by attaching functions
	 * to the sites init event and changing $CONFIG->site_id.
	 * 
	 * @uses $CONFIG
	 * @param string $event Event API required parameter
	 * @param string $object_type Event API required parameter
	 * @param null $object Event API required parameter
	 * @return true
	 */
		function sites_init($event, $object_type, $object) {
			global $CONFIG;
			
			$CONFIG->site_id = 1;
			
			trigger_event('init','sites');
			
			if ($site = get_data_row("select * from {$CONFIG->dbprefix}sites where id = 1")) {
				if (!empty($site->name))
					$CONFIG->sitename = $site->name;
				if (!empty($site->domain))
					$CONFIG->wwwroot = $site->domain;
			}
			
			return true;
		}
		
	// Register event handlers

		register_event_handler('init','system','sites_init',0);

?>