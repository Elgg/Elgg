<?php

	/**
	 * Elgg administration user system index
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
		
		
	// Display user browser

	// if search, perform search
		
		
	// extend user view with ban etc? - Or extend across the board (for admin only)
		
	// Display main admin menu
		page_draw(elgg_echo("admin:user"),elgg_view("admin/user"));
		
?>