<?php
	/**
	 * Elgg user settings system index
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	// Get the Elgg framework
		require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

	// Make sure only valid users can see this
		gatekeeper();
		
		
		
		/// Default settings
		
		
	// Display main admin menu
		page_draw(elgg_echo("admin"),elgg_view_layout("one_column", elgg_view("usersettings/main")));
?>