<?php
	/**
	 * Elgg user settings functions.
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
		gatekeeper();
		

	// Display main admin menu
		page_draw(elgg_echo("usersettings:plugins"),elgg_view_layout("one_column",elgg_view("usersettings/plugins", array('installed_plugins' => get_installed_plugins()))));
?>