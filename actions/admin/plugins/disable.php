<?php
	/**
	 * Disable plugin action.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php");
	
	// block non-admin users
	admin_gatekeeper();
	
	// Validate the action
	action_gatekeeper();
	
	// Get the user 
	$plugin = get_input('plugin');
	
	// Disable
	if (disable_plugin($plugin))
		system_message(sprintf(elgg_echo('admin:plugins:disable:yes'), $plugin));
	else
		register_error(sprintf(elgg_echo('admin:plugins:disable:no'), $plugin));		
		
	forward($_SERVER['HTTP_REFERER']);
	exit;
?>