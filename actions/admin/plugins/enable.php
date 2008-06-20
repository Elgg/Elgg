<?php
	/**
	 * Enable plugin action.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php");
	
	// block non-admin users
	admin_gatekeeper();
	
	// Get the user 
	$plugin = get_input('plugin');
	
	// Disable
	if (enable_plugin($plugin))
		system_message(sprintf(elgg_echo('admin:plugins:enable:yes'), $plugin));
	else
		system_message(sprintf(elgg_echo('admin:plugins:enable:no'), $plugin));		
		
	header("Location: {$CONFIG->wwwroot}admin/plugins/");
	exit;
?>