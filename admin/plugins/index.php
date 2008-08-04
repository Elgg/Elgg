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
		
	// Display main admin menu
		page_draw(elgg_echo("admin:plugins"),elgg_view_layout("two_column_left_sidebar", '', elgg_view_title(elgg_echo('admin:plugins')) . elgg_view("admin/plugins", array('installed_plugins' => get_installed_plugins()))));
		
?>