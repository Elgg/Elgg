<?php

	/**
	 * Elgg administration plugin system index
	 * This is a special page that permits the configuration of plugins in a standard way.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	// Get the Elgg framework
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

	// Make sure only valid admin users can see this
		admin_gatekeeper();
		
		// get list of plugins, itterate through - permit enable/disable & further config.
		
		
	// Display main admin menu
		page_draw(elgg_echo("admin:plugins"),elgg_view_layout("one_column", elgg_view("admin/plugins")));
		
?>