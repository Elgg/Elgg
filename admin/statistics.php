<?php

	/**
	 * Elgg administration statistics index
	 * This is a special page that displays a number of statistics
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @author Curverider Ltd
	 * @link http://elgg.org/
	 */

	// Get the Elgg framework
		require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

	// Make sure only valid admin users can see this
		admin_gatekeeper();
	
	// Set admin user for user block
		//set_page_owner($_SESSION['guid']);

	// Display main admin menu
		page_draw(elgg_echo("admin:statistics"),elgg_view_layout("two_column_left_sidebar",'',elgg_view_title(elgg_echo('admin:statistics')) . elgg_view("admin/statistics")));
		
?>
